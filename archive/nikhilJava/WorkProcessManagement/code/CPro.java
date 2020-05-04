import java.awt.*;
import java.awt.event.*;
import javax.swing.*;
import javax.swing.event.*;
import javax.swing.border.*;

public class CPro extends JFrame implements ActionListener
{
 JLabel l1,l2,l3;
 JButton b1,b2;
 Container c=this.getContentPane();
 JPanel p,p1;

 String border_type="Line";
 AbstractBorder border=new LineBorder(Color.black);

public CPro()
{

    setSize(500,400);
    setTitle("Calendar Entry");
    p=new JPanel();
    p1=new JPanel();
    l1=new JLabel("Registered Employee");
    l2=new JLabel("New Employee");
    l3=new JLabel("ENTRY FOR CALENDAR CUM SCHEDULE");

    b1=new JButton("Go");
    b2=new JButton("Go");
   
    c.setLayout(null);
    move(120,100);
    p1.setBounds(40,65,400,1);
    p.setBounds(65,120,340,150);
    l1.setBounds(110,140,250,40);
    l2.setBounds(110,210,250,40);
    b1.setBounds(285,145,50,30);
    b2.setBounds(285,210,50,30);
    l3.setBounds(75,40,420,40);
    l1.setFont(new Font("convecta", Font.BOLD,16));
    l1.setForeground(Color.black);
    l2.setFont(new Font("convecta", Font.BOLD,16));
    l2.setForeground(Color.black);
    b1.setFont(new Font("convecta", Font.BOLD,12));
    b2.setFont(new Font("convecta", Font.BOLD,12));
    l3.setFont(new Font("convecta", Font.BOLD,16));
    l3.setForeground(Color.black);
    
    b1.setForeground(Color.black);
    b2.setForeground(Color.black);
    
    this.setBackground(Color.gray);
    c.add(l1);c.add(l2);c.add(b1);
    c.add(b2);c.add("CENTER",l3);
    if(border_type.equals("Line"))
    {
          c.add(p1);
          c.add(p);
          p1.setBorder(border);
          p.setBorder(border);
          repaint();
          repaint();
    }

    b1.addActionListener(this);
    b2.addActionListener(this);
    
    setVisible(true);
 }

 public void actionPerformed(ActionEvent e)
 {
        Object o=e.getSource();
     
        if(o==b1)
        {
            rouser l=new rouser();
            setVisible(false);
        }
	else
	{
	  nuser u = new nuser();
	  setVisible(false);

	}
 }
}
                                                                                                                                                                                                                        
