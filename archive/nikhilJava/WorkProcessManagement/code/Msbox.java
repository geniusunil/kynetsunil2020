import javax.swing.*;
import java.awt.*;

public class Msbox extends JDialog
{
       public Msbox(Frame c,String mm )
       {
              setTitle("Confirmation");
              Object message="Time is been scheduled";
              JOptionPane.showMessageDialog(c,message);
              setVisible(true);
       }//end of Msbox constructor.
}//end of Msbox class.
