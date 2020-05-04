import javax.swing.*;
import java.awt.*;
import java.awt.event.*;
import java.io.*;
import java.sql.*;

public  class uinf extends JFrame implements ActionListener
{
        Runtime r;
        JLabel l;
        JComboBox cb;
        JButton go;
        String selectedname;
 
        public uinf()
        {
               setTitle("Selection Of Employee");
               setSize(500,400);
               move(120,100);
               l=new JLabel("Select the Employee's name You wish to View/Add");
               cb=new JComboBox();
               go=new JButton("GO");
  
               Container c=getContentPane();
               c.setLayout(null);
         
               l.setBounds(75,40,420,20);
               l.setFont(new Font("convecta",Font.BOLD,16));
               l.setForeground(Color.black);
               cb.setBounds(95,150,150,25);
               cb.setFont(new Font("convecta",Font.BOLD,16));
 
               go.setBounds(250,150,52,26);
               go.setFont(new Font("convecta",Font.BOLD,12));
               go.setForeground(Color.black);
      
               c.add(cb);c.add(l);c.add(go);

               setVisible(true);
               appendusers();
          
               go.addActionListener(this);
        }//end of uinf constructor.  
 
        public void appendusers()
        {
            try
            {
               Class cc=Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
               Connection con=DriverManager.getConnection("Jdbc:Odbc:pro","work","flow");
               Statement st=con.createStatement();
               ResultSet rs=st.executeQuery("select l_name from regs");
      
               while(rs.next())
               {
                     String name=rs.getString("l_name");
                     cb.addItem(name);
               }
               con.close();
            }
            catch(Exception ee)
            {
                 System.out.println("error in the uinf "+ee);
            }
        }//end of appendusers.

        public void actionPerformed(ActionEvent ae)
        {
               Object o=ae.getSource();
               if(o==go)
               {
                   selectedname=(String)cb.getSelectedItem();
                   todays td=new todays();
                   td.senditems(selectedname);
                   td.method();
                   td.display();
                 this.setVisible(false);
               }
        }//end of actionPerformed. 

      /*public static void main(String args[])
        {
               new uinf();
        } */ 

}//end of uinf class.
