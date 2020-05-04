import javax.swing.*;
import java.awt.*;

public class Mcbox extends JDialog
{
       public Mcbox(Frame c,String mm )
       {
              setTitle("Message");
              Object message="Ok inserted you can Proceed";
              JOptionPane.showMessageDialog(c,message);
              setVisible(true);
       }//end of Mcbox constructor.
}//end of Mcbox class.
