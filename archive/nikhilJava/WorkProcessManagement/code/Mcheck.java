import javax.swing.*;
import java.awt.*;

public class Mcheck extends JDialog
{
 public Mcheck(Frame c,String mm )
 {
  setTitle("Confirmation");
  Object message=mm;
  JOptionPane.showMessageDialog(c,message);
  setVisible(true);
 }
}
