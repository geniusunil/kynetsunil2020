import javax.swing.*;
import java.awt.*;

public class Mssbox extends JDialog
{
       public Mssbox(Frame c,String mm )
       {
             setTitle("Confirmation");
             Object message="Wrong Input Start Time Must"+
                            " be less than EndTime";
             JOptionPane.showMessageDialog(c,message);
             setVisible(true);
       }//end of Mssbox constructor.
}//end of Mssbox class.
