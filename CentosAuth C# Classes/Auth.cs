using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Collections.Specialized;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using static cent_auth.Handle;
namespace cent_auth
{
    class Auth
    {
        private static Dictionary<string, object> Vars = new Dictionary<string, object>();
        private static string Var_KEY { get; set; }
        private static string Var_SALT { get; set; }
        public static bool Login(string Username,string Password)
        {
            try
            {
                Start_Session();
                var values = new NameValueCollection();
                values["type"] = "login";
                values["username"] = Payload_ENCRYPT(Username);
                values["password"] = Payload_ENCRYPT(Password);
                values["hwid"] = Payload_ENCRYPT(HWID.getUniqueID());
                values["session_id"] = ENCRYPT_KEY;
                values["session_salt"] = ENCRYPT_SALT;
                string meme = Payload(values, true);
                dynamic json = JsonConvert.DeserializeObject(meme);
                switch ((string)json.result)
                {
                    case "success":
                        UserInfo.ID = (int)json.id;
                        UserInfo.Logged_In = bool.Parse((string)json.logged_in);
                        UserInfo.Username = (string)json.username;
                        UserInfo.Email = (string)json.email;
                        UserInfo.HWID = (string)json.hwid;
                        UserInfo.Expiry = (string)json.expiry;
                        UserInfo.Rank = (int)json.rank;
                        UserInfo.IP = (string)json.ip;
                        Vars = JsonConvert.DeserializeObject<Dictionary<string, object>>(json.vars.ToString());
                        Var_KEY = (string)json.session_id;
                        Var_SALT = (string)json.session_salt;
                        MessageBox.Show(string.Format("Welcome {0}, Login Success!", UserInfo.Username), "Login Success", MessageBoxButtons.OK, MessageBoxIcon.Asterisk);
                        return true;
                    case "invalid_details":
                        MessageBox.Show("Incorrect username/password", "Incorrect details", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        return false;
                    case "invalid_hwid":
                        MessageBox.Show("Incorrect HWID \n", "Incorrect HWID", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        return false;
                    case "hwid_updated":
                        MessageBox.Show("Your computer HWID has been updated\nYour account is now locked to this computer", "Computer HWID has been updated", MessageBoxButtons.OK, MessageBoxIcon.Information);
                        return false;
                    case "time_expired":
                        MessageBox.Show("Time EXPIRED! \n It appears your time is expired", "Time EXPIRED!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        return false;
                    case "net_error":
                        MessageBox.Show("It appears an Internet/Server error has occured", "Internet/Server Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        return false;
                    default:
                        MessageBox.Show("An unknown error has occured", "Unknown Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        return false;
                }
            }
            catch
            {
                return false;
            }
        }
        public static bool Register(string Username,string Email, string Password, string Repeat_Password,string Token)
        {
            try
            {
                if (Password == Repeat_Password)
                {
                    Start_Session();
                    var values = new NameValueCollection();
                    values["type"] = "register";
                    values["username"] = Payload_ENCRYPT(Username);
                    values["password"] = Payload_ENCRYPT(Password);
                    values["rep_pass"] = Payload_ENCRYPT(Repeat_Password);
                    values["hwid"] = Payload_ENCRYPT(HWID.getUniqueID());
                    values["email"] = Payload_ENCRYPT(Email);
                    values["token"] = Payload_ENCRYPT(Token);
                    values["session_id"] = ENCRYPT_KEY;
                    values["session_salt"] = ENCRYPT_SALT;
                    string meme = Payload(values, true);
                    dynamic json = JsonConvert.DeserializeObject(meme);
                    switch ((string)json.result)
                    {
                        case "success":
                            MessageBox.Show(string.Format("Welcome {0}, Register Success!", (string)json.username), "Register Success", MessageBoxButtons.OK, MessageBoxIcon.Asterisk);
                            return false;
                        case "invalid token":
                            MessageBox.Show("Invalid Token, please check your entries and try again", "Invalid Token", MessageBoxButtons.OK, MessageBoxIcon.Error);
                            return false;
                        case "invalid passwords":
                            MessageBox.Show("Passwords do not match \n Please check your passwords match", "Passwords Do Not Match", MessageBoxButtons.OK, MessageBoxIcon.Error);
                            return false;
                        case "email used":
                            MessageBox.Show("It appears this email has already been used", "Email Already Used", MessageBoxButtons.OK, MessageBoxIcon.Error);
                            return false;
                        case "invalid username":
                            MessageBox.Show("It appears this username is already taken", "Username Taken", MessageBoxButtons.OK, MessageBoxIcon.Error);
                            return false;
                        case "net_error":
                            MessageBox.Show("It appears an Internet/Server error has occured", "Internet/Server Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                            return false;
                        default:
                            MessageBox.Show("An unknown error has occurred!", "Unknown Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                            return false;
                    }
                }
                else
                {
                    MessageBox.Show("Passwords do not match \n Please check your passwords match", "Passwords Do Not Match", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    return false;
                }
            }
            catch
            {
                return false;
            }
        }
        public static bool Redeem_Token(string Username, string Password, string Token)
        {
            try
            {

                Start_Session();
                var values = new NameValueCollection();
                values["type"] = "redeem";
                values["username"] = Payload_ENCRYPT(Username);
                values["password"] = Payload_ENCRYPT(Password);
                values["hwid"] = Payload_ENCRYPT(HWID.getUniqueID());
                values["token"] = Payload_ENCRYPT(Token);
                values["session_id"] = ENCRYPT_KEY;
                values["session_salt"] = ENCRYPT_SALT;
                string meme = Payload(values, true);
                dynamic json = JsonConvert.DeserializeObject(meme);
                switch ((string)json.result)
                {
                    case "success":
                        MessageBox.Show(string.Format("Welcome {0} Token Redeem Success", (string)json.username), "Token Redeem Success", MessageBoxButtons.OK, MessageBoxIcon.Asterisk);
                        return false;
                    case "invalid token":
                        MessageBox.Show("Invalid Token Please Check Your Entries And Try Again", "Invalid Token", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        return false;
                    case "invalid details":
                        MessageBox.Show("Passwords Do Not Match \n Please Check Your Passwords Match", "Passwords Do Not Match", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        return false;
                    case "net_error":
                        MessageBox.Show("It Appears an Internet/Server Error Has Occured", "Internet/Server Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        return false;
                    default:
                        MessageBox.Show("An Unknown Error Has Occured And Info Forwarded Onto Our Dev", "Unknown Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        return false;
                }
            }
            catch
            {
                return false;
            }
        }
        public static object Var(string Name)
        {
            try
            {
                ENCRYPT_KEY = Var_KEY;
                ENCRYPT_SALT = Var_SALT;
                return Payload_DECRYPT(Vars[Name].ToString());
            }
            catch
            {
                return "unknown variable";
            }
        }
    }
}
