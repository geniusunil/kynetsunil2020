import java.awt.*;
import java.awt.event.*;
import javax.swing.event.*;
import javax.swing.*;
import java.sql.*;

public class nuser extends JFrame implements ActionListener
{
   int ip;
  JButton b1,b2;
  JLabel l,l1,l2,l3,l4,l5,l6,l7,l8,l9,l10,l11,ld;
  JTextField t1,t2,t3,t4,t5,t6,t22;
  JPasswordField p1,p2;
  JTextArea ta;
  JComboBox d,m,y,ts;
  String year[]=new String[5];
  String date[]=new String[31];
  String month[]=new String[12];
  String tsex[]=new String[3];
    public nuser()
    {
      setTitle("New Employee");
      l=new JLabel("NEW EMPLOYEE FILL-UP FORM");
      l.setBounds(235,30,850,20);
      l.setFont(new Font("convecta",Font.BOLD,20));
      l.setForeground(Color.black);
      String year[]={"1960","1961","1962","1963","1964","1965","1966","1967","1968","1969","1970","1971","1972","1973","1974","1975","1976","1977","1978","1979","1980","1981","1982","1983","1984","1985","1986","1987","1988","1999","2000","2001","2002","2003","2004"};
      String month[]={"JAN","FEB","MARCH","APRIL","MAY","JUNE","JULY","AUG","SEP","OCT","NOV","DEC"};
      String date[]={"1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"};
      String tsex[]={"MALE","FEMALE","---"};
      ld=new JLabel("IDNO");
      ld.setBounds(250,70,100,20);
      ld.setFont(new Font("convecta",Font.BOLD,12));
      ld.setForeground(Color.black); 
      t22=new JTextField(10);
      t22.setBounds(300,70,100,20);
      t22.setFont(new Font("convecta",Font.BOLD,12));
            
      l1=new JLabel("LOGIN NAME");
      l1.setBounds(210,100,200,20);
      l1.setFont(new Font("convecta",Font.BOLD,12));
      l1.setForeground(Color.black);
      l2=new JLabel("PASSWORD");
      l2.setBounds(210,130,200,20);
      l2.setFont(new Font("convecta",Font.BOLD,12));
      l2.setForeground(Color.black);
      l3=new JLabel("RE-ENTER PASSWORD");
      l3.setBounds(150,160,200,20);
      l3.setFont(new Font("convecta",Font.BOLD,12));
      l3.setForeground(Color.black);
      l4=new JLabel("FIRST NAME");
      l4.setBounds(210,190,200,20);
      l4.setFont(new Font("convecta",Font.BOLD,12));
      l4.setForeground(Color.black);
      l5=new JLabel("LAST NAME");
      l5.setBounds(210,220,200,20);
      l5.setFont(new Font("convecta",Font.BOLD,12));
      l5.setForeground(Color.black);
      l6=new JLabel("AGE");
      l6.setBounds(250,250,100,20);
      l6.setFont(new Font("convecta",Font.BOLD,12));
      l6.setForeground(Color.black);
      l7=new JLabel("DEPT NAME");
      l7.setBounds(210,280,200,20);
      l7.setFont(new Font("convecta",Font.BOLD,12));
      l7.setForeground(Color.black);
      l8=new JLabel("DEPT NUMBER");
      l8.setBounds(190,310,200,20);
      l8.setFont(new Font("convecta",Font.BOLD,12));
      l8.setForeground(Color.black);
      l9=new JLabel("SEX");
      l9.setBounds(250,340,200,20);
      l9.setFont(new Font("convecta",Font.BOLD,12));
      l9.setForeground(Color.black);
      l10=new JLabel("DATE OF BIRTH");
      l10.setBounds(190,370,200,20);
      l10.setFont(new Font("convecta",Font.BOLD,12));
      l10.setForeground(Color.black);
      l11=new JLabel("ADDRESS");
      l11.setBounds(220,400,200,20);
      l11.setFont(new Font("convecta",Font.BOLD,12));
      l11.setForeground(Color.black);
      b1=new JButton("SUBMIT");
      b1.setBounds(100,480,100,20);
      b1.setFont(new Font("convecta",Font.BOLD,12));
      b1.setForeground(Color.black);
      b2=new JButton("RESET");
      b2.setBounds(300,480,100,20);
      b2.setFont(new Font("convecta",Font.BOLD,12));
      b2.setForeground(Color.black);
      p1=new JPasswordField(15);
      p1.setBounds(300,130,200,20);
      p1.setFont(new Font("convecta",Font.BOLD,12));
      
      p2=new JPasswordField(15);
      p2.setBounds(300,160,200,20);
      p2.setFont(new Font("convecta",Font.BOLD,12));
      t1=new JTextField(20);
      t1.setBounds(300,100,200,20);
      t1.setFont(new Font("convecta",Font.BOLD,12));
      
      t2=new JTextField(20);
      t2.setBounds(300,190,200,20);
      t2.setFont(new Font("convecta",Font.BOLD,12));
      
      t3=new JTextField(20);
      t3.setBounds(300,220,200,20);
      t3.setFont(new Font("convecta",Font.BOLD,12));
      
      t4=new JTextField(3);
      t4.setBounds(300,250,50,20);
      t4.setFont(new Font("convecta",Font.BOLD,12));
      
      t5=new JTextField(15);
      t5.setBounds(300,280,200,20);
      t5.setFont(new Font("convecta",Font.BOLD,12));
      
      t6=new JTextField(10);
      t6.setBounds(300,310,80,20);
      t6.setFont(new Font("convecta",Font.BOLD,12));
      
      ta=new JTextArea(5,5);
      ta.setBounds(300,400,200,60);
      ta.setFont(new Font("convecta",Font.BOLD,20));
      
      d=new JComboBox(date);
      d.setBounds(300,370,50,20);
      d.setFont(new Font("convecta",Font.BOLD,12));
      d.setForeground(Color.black);
      m=new JComboBox(month);
      m.setBounds(350,370,80,20);
      m.setFont(new Font("convecta",Font.BOLD,12));
      m.setForeground(Color.black);
      y=new JComboBox(year);
      y.setBounds(430,370,80,20);
      y.setFont(new Font("convecta",Font.BOLD,12));
      y.setForeground(Color.black);
      ts=new JComboBox(tsex);
      ts.setBounds(300,340,80,20);
      ts.setFont(new Font("convecta",Font.BOLD,12));
      ts.setForeground(Color.black);
      Container c=getContentPane();
      c.setLayout(null);
      c.add(l);   c.add(t22);
      c.add(l1);  c.add(t1);
      c.add(l2);  c.add(p1);
      c.add(l3);  c.add(p2);
      c.add(l4);  c.add(t2);
      c.add(l5);  c.add(t3);
      c.add(l6);  c.add(t4);
      c.add(l7);  c.add(t5);
      c.add(l8);  c.add(t6);
      c.add(l9);  c.add(ts);
      c.add(l10); c.add(d); c.add(m); c.add(y);
      c.add(l11); c.add(ta);
      c.add(b1);  c.add(b2);
      c.add(ld);
      c.setBackground(Color.gray);
      setSize(700,550);
      setVisible(true);
      b1.addActionListener(this);
      b2.addActionListener(this);
      }
      
      public void submit()
      {
      
       try{
        Class c=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
        Connection con=DriverManager.getConnection("Jdbc:Odbc:pro","work","flow");
        Statement st=con.createStatement();
       ip=st.executeUpdate("insert into regs values('"+t22.getText()+"','"+t1.getText()+"','"+p1.getText()+"','"+p2.getText()+"','"+t2.getText()+"','"+t3.getText()+"','"+t4.getText()+"','"+t5.getText()+"','"+t6.getText()+"','"+ts.getSelectedItem()+"','"+d.getSelectedItem()+"','"+m.getSelectedItem()+"','"+y.getSelectedItem()+"','"+ta.getText()+"')");
        
       con.close();
          }
          catch( Exception e){
          System.out.println(e.getMessage());
          }
          }
      
      
      
      public void actionPerformed(ActionEvent evt)
      {
         Object o=evt.getSource();
         if (o==b1)
         {  
            Mcheck mb;
            Mcheck  mcb;
            if(p1.getText().equals(p2.getText()))
            {
              submit();

              if(ip>0)
              {
               mcb=new Mcheck(this,"Ok inserted You can Proceed");
              }      
            }
            else
            {
             mb= new Mcheck(this,"Password doesn't match");
            }
         }

         else
         if(o==b2)
         {
	      t22.setText("");t1.setText("");
              p1.setText(null);p2.setText(null);
              t2.setText("");t3.setText("");
              t4.setText("");t5.setText("");
              t6.setText("");ta.setText("");
         }
      }

      /*public static void main(String a[])
        {
            new nuser();
        }*/
} 



