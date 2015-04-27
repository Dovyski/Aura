import java.awt.AWTException;
import java.awt.Robot;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.event.InputEvent;
import java.awt.image.BufferedImage;
import java.awt.image.DataBuffer;
import java.awt.image.DataBufferByte;
import java.io.*;
import javax.imageio.ImageIO;
import java.net.URL;
import java.net.URLConnection;
import java.net.HttpURLConnection;

public class Spyglass {
    private boolean mActive;
    private String mWebEndpoint;
    private String mDeviceHash;
    private int mRefreshInterval;
    private Robot mRobot;
    private int mNoResponseCount;
    private boolean mDebug;

    public static void main(String theArgs[]) throws Exception {
        if(theArgs.length < 3) {
            System.out.println("Aura Spylass v1.0.0");
            System.out.println("Usage:\n\t./spyglass <endpoint> <hash> <refresh>\n");
            System.exit(1);
        }

        Spyglass s = new Spyglass(theArgs);
        s.run();
    }

    public Spyglass(String theArgs[]) throws Exception {
        mActive             = true;
        mWebEndpoint        = theArgs[0];
        mDeviceHash         = theArgs[1];
        mRefreshInterval    = Integer.parseInt(theArgs[2]);
        mRobot              = new Robot();
        mNoResponseCount    = 0;
        mDebug              = false;
    }

    private void debug(String theMsg) {
        if(mDebug) {
            debug(theMsg);
        }
    }

    private InputStream captureCurrentScreenFrame() throws Exception {
        // Capture the whole screen
        BufferedImage aScreen = mRobot.createScreenCapture(new Rectangle(Toolkit.getDefaultToolkit().getScreenSize()));

        // Write the image bytes to an output stream
        ByteArrayOutputStream aOs = new ByteArrayOutputStream();
        ImageIO.write(aScreen, "jpg", aOs);

        // Return an input stream that can be used to read our
        // just saved image bytes.
        return new ByteArrayInputStream(aOs.toByteArray());
    }

    private String sendCurrentScreenFrame(InputStream theInput) throws Exception {
        URL aUrl = new URL(mWebEndpoint + "?action=save-client-data&hash=" + mDeviceHash);
        HttpURLConnection aCon = (HttpURLConnection)aUrl.openConnection();

        aCon.setDoInput(true);
        aCon.setDoOutput(true);
        aCon.setUseCaches(false);
        aCon.setRequestProperty("Content-Type", "image/jpeg");
        aCon.setRequestMethod("POST");

        OutputStream aOut = aCon.getOutputStream();
        copy(theInput, aCon.getOutputStream());

        aOut.flush();
        aOut.close();

        BufferedReader aReader = new BufferedReader(new InputStreamReader(aCon.getInputStream()));

        // Get the server response
        String aLine, aReturn = "";

        for (aLine = aReader.readLine(); aLine != null;  aLine = aReader.readLine()) {
            aReturn += aLine;
        }

        return aReturn;
    }

    public void run() throws Exception {
        while(mActive) {
            String aResponse;

            InputStream aInput = captureCurrentScreenFrame();
            aResponse = sendCurrentScreenFrame(aInput);

            if(aResponse != null) {
                handleServerResponse(aResponse);
            }

            Thread.sleep(mRefreshInterval);
        }
    }

    private void handleServerResponse(String theResponse) throws Exception {
        String aResponse = theResponse.replace('"', ' ').trim();

        if(aResponse.length() == 0) {
            debug("No server response");
            mNoResponseCount++;

            if(mNoResponseCount >= 10) {
                // Web client probably disconnected. The party is over!
                mActive = false;
            }

            return;
        }

        debug("Server response: " + aResponse);
        String[] aCommands = aResponse.split(";");

        mNoResponseCount = 0;

        for(int i = 0; i < aCommands.length; i++) {
            String[] aParts = ((String)aCommands[i]).split(":");

            switch(aParts[0]) {
                case "mv":
                    handleMouseMovement(aParts);
                    break;

                case "mp":
                    handleMouseClick(aParts, true);
                    break;

                case "mr":
                    handleMouseClick(aParts, false);
                    break;

                case "kp":
                    handleKey(aParts, true);
                    break;

                case "kr":
                    handleKey(aParts, false);
                    break;

                case "end":
                    mActive = false;
                    break;

                default:
                    debug("Unknown op: " + aParts[0]);
            }
        }
    }

    private void handleMouseMovement(String[] theParts) throws Exception {
        String[] aCoords = theParts[1].split(",");

        int aX = Integer.parseInt(aCoords[0]),
            aY = Integer.parseInt(aCoords[1]);

        mRobot.mouseMove(aX, aY);
    }

    private void handleMouseClick(String[] theParts, boolean thePress) throws Exception {
        if(thePress) {
            debug("mousePress");
            mRobot.mousePress(InputEvent.BUTTON1_DOWN_MASK);
        } else {
            debug("mouseRelease");
            mRobot.mouseRelease(InputEvent.BUTTON1_DOWN_MASK);
        }
    }

    private void handleKey(String[] theParts, boolean thePress) throws Exception {
        int aKey = Integer.parseInt(theParts[1]);

        if(thePress) {
            debug("keyPress - " + aKey);
            mRobot.keyPress(aKey);
        } else {
            debug("keyRelease - " + aKey);
            mRobot.keyRelease(aKey);
        }
    }

    // This code was copied from the Jacarta project.
    protected static long copy(InputStream input, OutputStream output) throws IOException {
        byte[] buffer = new byte[12288]; // 12K
        long count = 0L;
        int n = 0;
        while (-1 != (n = input.read(buffer))) {
            output.write(buffer, 0, n);
            count += n;
        }
        return count;
    }
}
