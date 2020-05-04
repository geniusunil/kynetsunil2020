import javax.swing.*;
import java.awt.*;

public class Mtbox extends JDialog
{
       public Mtbox(Frame c,String mm )
       {
              setTitle("Time message");
              Object message="Time already scheduled...";
              JOptionPane.showMessageDialog(c,message);
              setVisible(true);
       }//end of Mtbox constructor.
}//end of Mtbox class.
