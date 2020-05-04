import javax.swing.*;
import java.awt.*;

public class Mbox extends JDialog
{
       public Mbox(Frame c,String mm )
       {
             setTitle("Message");
             Object message="Password doesn't match";
             JOptionPane.showMessageDialog(c,message);
             setVisible(true);
       }//end of Mbox constructor.
}//end of Mbox class
