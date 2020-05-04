import javax.swing.*;
import java.awt.*;

public class Mmbox extends JDialog
{
       public Mmbox(Frame c,String mm )
       {
              setTitle("Message");
              Object message="Invalid Entry... Try Again...";
              JOptionPane.showMessageDialog(c,message);
              setVisible(true);
       }//end of Mmbox constructor.
}//end of Mmbox class.
