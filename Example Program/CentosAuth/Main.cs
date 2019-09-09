using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace cent_auth
{
    public partial class Main : Form
    {
        public Main()
        {
            InitializeComponent();
        }

        private void Main_Load(object sender, EventArgs e)
        {
            MessageBox.Show(Auth.Var("meme").ToString());
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (Auth.Redeem_Token(UserInfo.Username, textBox1.Text, textBox2.Text))
            {

            }
            else
            {

            }
        }
    }
}
