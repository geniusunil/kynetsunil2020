import java.awt.*;
import javax.swing.*;
import java.awt.event.*;
import javax.swing.border.*;
import javax.swing.event.*;

public class Option extends JFrame implements ActionListener
{

       JButton ok;
       JLabel l,l1,l2,l3,sel;
       JTextField tf;
       JPanel panel;
       String border_type="Titled";
       AbstractBorder border=new TitledBorder("             ");

       public static int i;

       public Option()
       {
              setTitle("Main Entry");
              setSize(500,400);
              move(120,100);
              sel=new JLabel("Select");
              sel.setForeground(Color.black);
              l1=new JLabel("1.Supervisor");
              l2=new JLabel("2.Employee");
              l3=new JLabel("3.Read-Only Purpose");
              
              ok=new JButton("OK");
              l=new JLabel("ENTER YOUR CHOICE");
              tf=new JTextField(10);
 
              panel =new JPanel();
              panel.setBounds(75,75,370,160);
              panel.setBackground(Color.gray);              
             
              Container c=getContentPane();
              c.setBackground(Color.gray);
              c.setLayout(null);
              sel.setBounds(85,65,50,30);
              l.setBounds(100,200,200,20);
              l.setFont(new Font("convecta",Font.BOLD,16));
              l.setForeground(Color.black);
              l1.setBounds(100,100,200,20);
              l1.setFont(new Font("convecta",Font.BOLD,16));
              l1.setForeground(Color.black);
              l2.setBounds(100,130,200,20);
              l2.setFont(new Font("convecta",Font.BOLD,16));
              l2.setForeground(Color.black);
              l3.setBounds(100,160,200,20);
              l3.setFont(new Font("convecta",Font.BOLD,16));
              l3.setForeground(Color.black);

              tf.setBounds(300,200,30,20);
              tf.setFont(new Font("convecta",Font.BOLD,16));
   
              ok.setBounds(355,200,60,20);
              ok.setFont(new Font("convecta",Font.BOLD,16));
              c.add(sel);
              c.add(l);c.add(ok);c.add(tf);
              c.add(l1);c.add(l2);c.add(l3);
            
              tf.requestFocus();
              tf.addNotify();
    
              ok.addActionListener(this);
              c.setBackground(Color.lightGray);
            
              if(border_type.equals("Titled"))
              {
                      c.add(panel);
                      panel.setBorder(border);
                      repaint();
              }
              setVisible(true);
     }//end of Option constructor.

     public void actionPerformed(ActionEvent e)
     {
   
		String s=tf.getText();
            i=Integer.parseInt(s);
          
            if(i==1&&(e.getSource()==ok))
            {
                   ouser a3=new ouser();
            }
            else
            if(i==2&&(e.getSource()==ok))
            {
                  CPro  a2=new CPro();
            }
            else
            if(i==3&&(e.getSource()==ok))
            {
                  ouser a1=new ouser();
            }
      }//end of actionPerformed.
      
      public static void main(String a[])
      {
             new Option();
      }//end of main method.

}//end of Option class.

