import java.awt.AWTException;
import java.awt.Robot;
import java.awt.Rectangle;
import java.awt.Toolkit;
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
    }

    private void captureCurrentScreenFrame() throws Exception {
        // Capture the whole screen
        BufferedImage aScreen = mRobot.createScreenCapture(new Rectangle(Toolkit.getDefaultToolkit().getScreenSize()));

        // Save as JPEG
        File aFile = new File("screencapture.jpg");
        ImageIO.write(aScreen, "jpg", aFile);
    }

    private String sendCurrentScreenFrame() throws Exception {
        URL aUrl = new URL(mWebEndpoint + "&hash=" + mDeviceHash);
        HttpURLConnection aCon = (HttpURLConnection)aUrl.openConnection();

        aCon.setDoInput(true);
        aCon.setDoOutput(true);
        aCon.setUseCaches(false);
        aCon.setRequestProperty("Content-Type", "image/jpeg");
        aCon.setRequestMethod("POST");

        InputStream aIn = new FileInputStream("screencapture.jpg");
        OutputStream aOut = aCon.getOutputStream();

        copy(aIn, aCon.getOutputStream());

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

            captureCurrentScreenFrame();
            aResponse = sendCurrentScreenFrame();

            if(aResponse != null) {
                handleServerResponse(aResponse);
            }

            Thread.sleep(mRefreshInterval);
        }
    }

    private void handleServerResponse(String theResponse) throws Exception {
        String aResponse = theResponse.replace('"', ' ').trim();
        String[] aCommands = aResponse.split(";");

        System.out.println("Server response: " + aResponse);

        for(int i = 0; i < aCommands.length; i++) {
            String[] aParts = ((String)aCommands[i]).split(":");

            switch(aParts[0]) {
                case "mv":
                    handleMouseMovement(aParts);
                    break;

                case "mp":
                    System.out.println("MP");
                    break;

                case "mr":
                    System.out.println("MR");
                    break;

                case "kp":
                    System.out.println("KP");
                    break;

                case "kr":
                    System.out.println("KR");
                    break;
            }
        }
    }

    private void handleMouseMovement(String[] theParts) throws Exception {
        String[] aCoords = theParts[1].split(",");

        int aX = Integer.parseInt(aCoords[0]),
            aY = Integer.parseInt(aCoords[1]);

        mRobot.mouseMove(aX, aY);
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
