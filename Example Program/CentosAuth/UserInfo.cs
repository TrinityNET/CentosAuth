using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace cent_auth
{
    class UserInfo
    {
        public static int ID { get; set; }
        public static string Username { get; set; }
        public static string Email { get; set; }
        public static string HWID { get; set; }
        public static string Expiry { get; set; }
        public static int Rank { get; set; }
        public static string IP { get; set; }
        public static bool Expired { get; set; }
        public static bool Logged_In { get; set; }
    }
}
