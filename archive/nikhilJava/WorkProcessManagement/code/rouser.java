import java.awt.*;
import javax.swing.*;
import javax.swing.event.*;
import java.sql.*;
import java.awt.event.*;

import javax.swing.border.*;

public class rouser extends JFrame implements ActionListener
{
       ResultSet rs;
       String ln,pw; int idn;
       Runtime r;
       boolean x;
       JButton b1;
       JLabel l1,l2,l3;
       JTextField t1;
       JPasswordField  p1;
       JPanel p,pp1;
       String border_type="Line";
       AbstractBorder border=new LineBorder(Color.black,1);
  
       public rouser()
       { 
              setSize(500,400);
              move(120,100);
              setTitle("Calender Login");

              p=new JPanel();
              pp1=new JPanel();
              pp1.setBounds(40,65,400,1);
              p.setBounds(30,120,420,175);
              l3 =new JLabel("LOGIN FOR CALENDER AND SCHEDULE");
              l3.setForeground(Color.black);
              l1=new JLabel("NAME");
              t1=new JTextField(15);
              l2=new JLabel("PASSWORD");
              p1=new JPasswordField(15);
              b1=new JButton("CONTINUE");
              l1.setBounds(95,150,300,30);
              l1.setFont(new Font("convecta",Font.BOLD,14));
              t1.setBounds(250,150,170,23);
              t1.setFont(new Font("convecta",Font.BOLD,15));
              l2.setBounds(95,180,300,30);
              l2.setFont(new Font("convecta",Font.BOLD,14));
              p1.setBounds(250,180,170,23);
              p1.setFont(new Font("convecta",Font.BOLD,15));
              b1.setBounds(150,235,150,25);
              b1.setFont(new Font("convecta",Font.BOLD,15));
              l3.setBounds(75,40,420,40);
              l3.setFont(new Font("convecta",Font.BOLD,16));
              l1.setForeground(Color.black);
              l2.setForeground(Color.black);
      
              Container contain=getContentPane();
              contain.add(l1);contain.add(t1);
              contain.add(l2);contain.add(p1);
              contain.add(b1);contain.add(l3);
      
              contain.setLayout(null);

              if(border_type.equals("Line"))
              {
                contain.add(p);
                contain.add(pp1);
                p.setBorder(border);
                pp1.setBorder(border);
                repaint();
                repaint();
              }  

//              contain.setBackground(Color.gray);
       
              setVisible(true);
 
              b1.addActionListener(this);

       }//end of rouser java.

       public void check()
       {
              Mmbox m1;
              try
              {
                 Class c=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                 Connection con=DriverManager.getConnection("Jdbc:Odbc:pro");
                 Statement st=con.createStatement();
                 rs=st.executeQuery("select idno,l_name,pwd from regs where l_name='"+t1.getText()+"' and pwd='"+p1.getText()+"'");
    
                 while(rs.next())
                 {
                      idn=rs.getInt("idno");
                      ln=rs.getString("l_name");
                      pw=rs.getString("pwd");
                 }
		if(ln==null || pw==null)
		{
                   m1=new Mmbox(this,"Message");
                 	m1.setVisible(false);	
                 }
                 if(ln.equals(t1.getText()) && pw.equals(p1.getText()))
                 {
       
                      try
                      {
                             todays td=new todays();
                             td.senditems(ln);td.method();
                             td.display();
                             this.setVisible(false);
                      }
                      catch(Exception eee)
                      {
                             System.out.println("ERROR"+eee);
                      }
                }
                else
                {
                      m1=new Mmbox(this,"venki");
                  	m1.setVisible(false);	

                }
                con.close();
                }
                catch(Exception ec)
                {
                      System.out.println(""+ec);
                }
         }//end of check method. 

         public void actionPerformed(ActionEvent e)
         {
                if(e.getSource()==b1)
                {
                     check();
                }
                                             
         }//end of actionPerformed.
}//end of rouser class.
