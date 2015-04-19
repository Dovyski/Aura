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
    public static void main(String args[]) throws Exception {
        while(true) {
            // capture the whole screen
            BufferedImage screen = new Robot().createScreenCapture(new Rectangle(Toolkit.getDefaultToolkit().getScreenSize()));

            // Save as JPEG
            File file = new File("screencapture.jpg");
            ImageIO.write(screen, "jpg", file);

            // TODO: get this URL from config/argv
            URL url = new URL("http://dev.local.com/ncc.cc.uffs.edu.br/admin/aura/brain.php?method=spyglass&hash=153c40a61c60a0f1d42b01c679470cf5");
            HttpURLConnection con = (HttpURLConnection)url.openConnection();
            con.setDoInput(true);
            con.setDoOutput(true);
            con.setUseCaches(false);
            con.setRequestProperty("Content-Type", "image/jpeg");
            con.setRequestMethod("POST");
            InputStream in = new FileInputStream("screencapture.jpg");
            OutputStream out = con.getOutputStream();
            copy(in, con.getOutputStream());
            out.flush();
            out.close();
            BufferedReader r = new BufferedReader(new InputStreamReader(con.getInputStream()));

            // obviously it is not required to print the response. But you have
            // to call con.getInputStream(). The connection is really established only
            // when getInputStream() is called.
            System.out.println("Output:");
            for (String line = r.readLine(); line != null;  line = r.readLine()) {
                handleRemoteInteractions(line);
            }

            Thread.sleep(2000);
        }
    }

    private static void handleRemoteInteractions(String theRemote) throws Exception {
        String aRemote = theRemote.replace('"', ' ').trim();
        String[] aCommands = aRemote.split(";");

        System.out.println("Remote: " + aRemote);

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

    private static void handleMouseMovement(String[] theParts) throws Exception {
        String[] aCoords = theParts[1].split(",");

        Robot aRobot = new Robot();
        aRobot.mouseMove(Integer.parseInt(aCoords[0]), Integer.parseInt(aCoords[1]));
    }

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
