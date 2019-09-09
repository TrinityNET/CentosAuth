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
    public partial class Register : Form
    {
        public Register()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (Auth.Register(textBox1.Text, textBox4.Text, textBox2.Text, textBox3.Text, textBox5.Text))
            {
                Close();
            }
            else
            {

            }
        }
    }
}
