import java.sql.*;
 public class Sample
 {
        public static void main(String args[])
        {
                try
                {
                        Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                        Connection con=DriverManager.getConnection("Jdbc:Odbc:pro","work","flow");
                        Statement st=con.createStatement();
                        ResultSet rs=st.executeQuery("desc idno from regs");
                        int s=rs.getInt("1");
                        System.out.println(s+ " ");
                        con.close();
                }
                catch(Exception e)
                {
                        System.out.println("in sample error "+e);
                }
        }
}
                
