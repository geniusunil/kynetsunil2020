import java.sql.*;
 public class Sample
 {
        public static void main(String args[])
        {
                try
                {
                        Class.forName("sun.jdbc.odbc.JdbcOdbcDriver");
                        Connection con=DriverManager.getConnection("jdbc:odbc:pro");
                        Statement st=con.createStatement();
                        ResultSet rs=st.executeQuery("select idno from regs");
                        int s=rs.getInt("1");
                        System.out.println(s+ " ");
                        con.close();
                }
                catch(Exception e)
                {
                        System.out.println(" "+e);
                }
        }
}
                
