using CM.TOS.V4.Common.Component;
using System;
using System.Collections.Generic;
using System.IO;
using System.IO.Compression;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CM.TOS.V4.Common.Utility
{
    public class CompressUtiliy
    {
        private static long zipsizemin = 800000;
        public static string headflag = "ZIP";
        public static string Compress(string str, bool needheadflag, Encoding enc)
        {
            string ret = str;
            if (ret.Length < zipsizemin || ret.StartsWith(headflag)) return str;
            Byte[] bTytes = enc.GetBytes(str);
            Byte[] retbytes= Compress(bTytes, needheadflag,enc);

            return enc.GetString(retbytes);
        }
        public static char[] CompressToChar(char[] str, bool needheadflag, Encoding enc)
        {
            if (str.Length < zipsizemin) return str;
            Byte[] bTytes = enc.GetBytes(str);
            Byte[] retbytes = Compress(bTytes, needheadflag, enc);

            return enc.GetChars(retbytes);
        }
        public static byte[] CompressToByte(char[] str, bool needheadflag, Encoding enc)
        {            
            if (str.Length < zipsizemin) return enc.GetBytes(str);

            Byte[] bTytes = enc.GetBytes(str);
            Byte[] retbytes = Compress(bTytes, needheadflag, enc);

            return retbytes;
        }
        private static byte[] Compress(byte[] bTytes, bool needheadflag, Encoding enc)
        {
            byte[] ret = null;
            try
            {
                
                bTytes = Compress(bTytes);

                using (MemoryStream ms = new MemoryStream())
                {
                    //先写入ZIP，后面再写实际内容
                    if (needheadflag)
                    {
                        Byte[] bTytesZip = enc.GetBytes(headflag);
                        ms.Write(bTytesZip, 0, bTytesZip.Length);
                    }
                    ms.Write(bTytes, 0, bTytes.Length);

                    ret = ms.ToArray();
                }
            }
            catch (Exception ex)
            {
                LocalLoggingService.Error("CompressUtiliy.Compress: {0}", ex);
            }
            finally
            {
               
            }
            return ret;
        }
        private static byte[] Compress(byte[] bytes)
        {            
            try
            {
                using (System.IO.MemoryStream ms = new System.IO.MemoryStream())
                {
                    System.IO.Compression.GZipStream Compress = new System.IO.Compression.GZipStream(ms, System.IO.Compression.CompressionMode.Compress);
                    Compress.Write(bytes, 0, bytes.Length);
                    Compress.Close();
                    return ms.ToArray();
                }
            }
            catch (System.Exception ex)
            {
                LocalLoggingService.Error(ex.Message);
                return bytes;
            }

        }

        public static byte[] DeCompress(byte[] inputData, Encoding enc)
        {
            
            if (inputData == null || inputData.Length < 3) return inputData;
            if (!enc.GetString(inputData, 0, 3).Equals(headflag))
            {
                return inputData;
            }

            byte[] decompressedData = null;
            try
            {
              
                using (MemoryStream outputStream = new MemoryStream())
                {
                    using (MemoryStream inputStream = new MemoryStream(inputData, 3, inputData.Length - 3))
                    {
                        using (GZipStream zip = new GZipStream(inputStream, CompressionMode.Decompress))
                        {
                            zip.CopyTo(outputStream);
                        }
                    }
                    decompressedData = outputStream.ToArray();
                }
            }
            catch (Exception ex)
            {
                LocalLoggingService.Error("DeCompress,Err:{0}", ex.Message);

                decompressedData = inputData;
            }

            return decompressedData;
        }
    }
}
