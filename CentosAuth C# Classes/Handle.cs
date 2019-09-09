using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Collections.Specialized;
using System.IO;
using System.Linq;
using System.Management;
using System.Net;
using System.Security.Cryptography;
using System.Text;
using System.Threading.Tasks;

namespace cent_auth
{
    class Handle
    {
        public static string URL = "https://yoursite.com";
        public static string ENCRYPT_KEY { get; set; }
        public static string ENCRYPT_SALT { get; set; }
        public static string Payload_DECRYPT(string value)
        {
            string message = value;
            string password = Encoding.Default.GetString(Convert.FromBase64String(ENCRYPT_KEY));
            SHA256 mySHA256 = SHA256Managed.Create();
            byte[] key = mySHA256.ComputeHash(Encoding.ASCII.GetBytes(password));
            byte[] iv = Encoding.ASCII.GetBytes(Encoding.Default.GetString(Convert.FromBase64String(ENCRYPT_SALT)));
            string decrypted = String_Encryption.DecryptString(message, key, iv);
            return decrypted;
        }
        public static string Payload_ENCRYPT(string value)
        {
            string message = value;
            string password = Encoding.Default.GetString(Convert.FromBase64String(ENCRYPT_KEY));
            SHA256 mySHA256 = SHA256Managed.Create();
            byte[] key = mySHA256.ComputeHash(Encoding.ASCII.GetBytes(password));
            byte[] iv = Encoding.ASCII.GetBytes(Encoding.Default.GetString(Convert.FromBase64String(ENCRYPT_SALT)));
            string decrypted = String_Encryption.EncryptString(message, key, iv);
            return decrypted;
        }
        private static string Session_ID(int length)
        {
            Random random = new Random();
            const string chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
            return new string(Enumerable.Repeat(chars, length)
              .Select(s => s[random.Next(s.Length)]).ToArray());
        }
        public static void Start_Session()
        {
            ENCRYPT_KEY = Convert.ToBase64String(Encoding.Default.GetBytes(Session_ID(32)));
            ENCRYPT_SALT = Convert.ToBase64String(Encoding.Default.GetBytes(Session_ID(16)));
        }
        public static string Payload(NameValueCollection Values, bool encrypted = false)
        {
            try
            {
                switch (encrypted)
                {
                    case false:
                        return Encoding.Default.GetString(new WebClient().UploadValues(URL, Values));
                    case true:
                        SHA256 mySHA256 = SHA256Managed.Create();
                        byte[] key = mySHA256.ComputeHash(Encoding.ASCII.GetBytes(Encoding.Default.GetString(Convert.FromBase64String(ENCRYPT_KEY))));
                        byte[] iv = Encoding.ASCII.GetBytes(Encoding.Default.GetString(Convert.FromBase64String(ENCRYPT_SALT)));
                        string decrypted = String_Encryption.DecryptString(Encoding.Default.GetString(new WebClient().UploadValues(URL, Values)), key, iv);
                        return decrypted;
                    default:
                        Dictionary<string, object> ERROR = new Dictionary<string, object>();
                        ERROR.Add("result", "net_error");
                        return JsonConvert.SerializeObject(ERROR);

                }
            }
            catch (WebException E)
            {
                Dictionary<string, object> ERROR = new Dictionary<string, object>();
                HttpWebResponse response = (HttpWebResponse)E.Response;
                switch (response.StatusCode)
                {
                    case HttpStatusCode.NotFound:
                        ERROR.Add("result", "net_error");
                        break;
                    case HttpStatusCode.RequestEntityTooLarge:
                        ERROR.Add("result", "net_error");
                        break;
                    case HttpStatusCode.ServiceUnavailable:
                        ERROR.Add("result", "net_error");
                        break;
                    case HttpStatusCode.Forbidden:
                        ERROR.Add("result", "net_error");
                        break;
                }
                ERROR.Add("result", "net_error");
                return JsonConvert.SerializeObject(ERROR);
            }
        }
        public static string Payload(string URL, NameValueCollection Values, bool encrypted = false)
        {
            try
            {
                switch (encrypted)
                {
                    case false:
                        return Encoding.Default.GetString(new WebClient { Proxy = null }.UploadValues(URL, Values));
                    case true:
                        string message = Encoding.Default.GetString(new WebClient { Proxy = null }.UploadValues(URL, Values));
                        string password = Encoding.Default.GetString(Convert.FromBase64String(ENCRYPT_KEY));
                        SHA256 mySHA256 = SHA256Managed.Create();
                        byte[] key = mySHA256.ComputeHash(Encoding.ASCII.GetBytes(password));
                        byte[] iv = Encoding.ASCII.GetBytes(Encoding.Default.GetString(Convert.FromBase64String(ENCRYPT_SALT)));
                        string decrypted = String_Encryption.DecryptString(message, key, iv);
                        return decrypted;
                    default:
                        Dictionary<string, object> ERROR = new Dictionary<string, object>();
                        ERROR.Add("result", "net_error");
                        return JsonConvert.SerializeObject(ERROR);

                }
            }
            catch (WebException E)
            {
                Dictionary<string, object> ERROR = new Dictionary<string, object>();
                HttpWebResponse response = (HttpWebResponse)E.Response;
                switch (response.StatusCode)
                {
                    case HttpStatusCode.NotFound:
                        ERROR.Add("result", "net_error");
                        break;
                    case HttpStatusCode.RequestEntityTooLarge:
                        ERROR.Add("result", "net_error");
                        break;
                    case HttpStatusCode.ServiceUnavailable:
                        ERROR.Add("result", "net_error");
                        break;
                    case HttpStatusCode.Forbidden:
                        ERROR.Add("result", "net_error");
                        break;
                }
                Console.WriteLine(E.ToString());
                ERROR.Add("result", "net_error");
                return JsonConvert.SerializeObject(ERROR);
            }
        }
        class String_Encryption
        {
            public static string EncryptString(string plainText, byte[] key, byte[] iv)
            {
                Aes encryptor = Aes.Create();
                encryptor.Mode = CipherMode.CBC;
                encryptor.Key = key;
                encryptor.IV = iv;
                MemoryStream memoryStream = new MemoryStream();
                ICryptoTransform aesEncryptor = encryptor.CreateEncryptor();
                CryptoStream cryptoStream = new CryptoStream(memoryStream, aesEncryptor, CryptoStreamMode.Write);
                byte[] plainBytes = Encoding.ASCII.GetBytes(plainText);
                cryptoStream.Write(plainBytes, 0, plainBytes.Length);
                cryptoStream.FlushFinalBlock();
                byte[] cipherBytes = memoryStream.ToArray();
                memoryStream.Close();
                cryptoStream.Close();
                string cipherText = Convert.ToBase64String(cipherBytes, 0, cipherBytes.Length);
                return cipherText;
            }

            public static string DecryptString(string cipherText, byte[] key, byte[] iv)
            {
                Aes encryptor = Aes.Create();
                encryptor.Mode = CipherMode.CBC;
                encryptor.Key = key;
                encryptor.IV = iv;
                MemoryStream memoryStream = new MemoryStream();
                ICryptoTransform aesDecryptor = encryptor.CreateDecryptor();
                CryptoStream cryptoStream = new CryptoStream(memoryStream, aesDecryptor, CryptoStreamMode.Write);
                string plainText = String.Empty;
                try
                {
                    byte[] cipherBytes = Convert.FromBase64String(cipherText);
                    cryptoStream.Write(cipherBytes, 0, cipherBytes.Length);
                    cryptoStream.FlushFinalBlock();
                    byte[] plainBytes = memoryStream.ToArray();
                    plainText = Encoding.ASCII.GetString(plainBytes, 0, plainBytes.Length);
                }
                finally
                {
                    memoryStream.Close();
                    cryptoStream.Close();
                }
                return plainText;
            }
        }
    }
    public class HWID
    {
        public static string getUniqueID()
        {
            string drive = "C";
            if (drive == string.Empty)
            {
                foreach (DriveInfo compDrive in DriveInfo.GetDrives())
                {
                    if (compDrive.IsReady)
                    {
                        drive = compDrive.RootDirectory.ToString();
                        break;
                    }
                }
            }
            if (drive.EndsWith(":\\"))
            {
                drive = drive.Substring(0, drive.Length - 2);
            }
            string volumeSerial = getVolumeSerial(drive);
            string cpuID = getCPUID();
            return cpuID.Substring(13) + cpuID.Substring(1, 4) + volumeSerial + cpuID.Substring(4, 4);
        }
        static string getVolumeSerial(string drive)
        {
            ManagementObject disk = new ManagementObject(@"win32_logicaldisk.deviceid=""" + drive + @":""");
            disk.Get();
            string volumeSerial = disk["VolumeSerialNumber"].ToString();
            disk.Dispose();
            return volumeSerial;
        }
        public static string PCUSERNAME()
        {
            return Environment.UserName;
        }
        public static string PCNAME()
        {
            return Environment.MachineName;
        }
        static string getCPUID()
        {
            string cpuInfo = "";
            ManagementClass managClass = new ManagementClass("win32_processor");
            ManagementObjectCollection managCollec = managClass.GetInstances();
            foreach (ManagementObject managObj in managCollec)
            {
                if (cpuInfo == "")
                {
                    cpuInfo = managObj.Properties["processorID"].Value.ToString();
                    break;
                }
            }
            return cpuInfo;
        }
    }
}
