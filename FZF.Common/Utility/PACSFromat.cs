using System;
using System.Collections.Generic;
using System.Data;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml;
using CM.TOS.V4.Common.Extension;

namespace CM.TOS.V4.Common.Utility
{
    public class PACSFromat
    {
        /// <summary>
        /// Formats the string to data set.
        /// </summary>
        /// <param name="param">The param.</param>
        /// <returns></returns>
        /// 作    者：zcnie
        /// 创建时间：2011-1-15
        public static DataSet FormatStringToDataSet(string param)
        {
            DataSet ds = new DataSet();
            string[] tables = param.Split(new string[] { "\u0003" }, StringSplitOptions.RemoveEmptyEntries);//一个table最后以\u0003结束
            foreach (string curTable in tables)
            {
                DataTable dt = new DataTable();
                string[] namefieldValue = curTable.Split(new string[] { "\u0004", "\u0002" }, StringSplitOptions.RemoveEmptyEntries);
                if (namefieldValue.Length > 1)
                {
                    //取固定格式内容。表名、主键、操作。 联合主键以逗号分隔。","
                    string[] tablenameKey = namefieldValue[0].Split(new string[] { "\u0001" }, StringSplitOptions.None);

                  
                    //创建列。
                    string[] fields = namefieldValue[1].Split(new string[] { "\u0001" }, StringSplitOptions.RemoveEmptyEntries);
                    foreach (string curfield in fields)
                    {
                        dt.Columns.Add(curfield);
                    }
                    //填充数据到表。
                    string[] rows = new string[namefieldValue.Length -2];

                    Array.Copy( namefieldValue, 2, rows,0,namefieldValue.Length -2);

                    rows.AsParallel().Each(s =>
                        {
                            DataRow dr = dt.NewRow();
                            string[] values = s.Split(new string[] { "\u0001" }, StringSplitOptions.None);
                            for (int j = 0; j < values.Length; j++) dr[j] = values[j];
                            dt.Rows.Add(dr);
                        }
                    );
                    //for (int i = 2; i < namefieldValue.Length; i++)
                    //{
                    //    DataRow dr = dt.NewRow();
                    //    string[] values = namefieldValue[i].Split(new string[] { "\u0001" }, StringSplitOptions.None);
                    //    for (int j = 0; j < values.Length; j++)
                    //    {
                    //        if (dt.Columns[j].DataType == typeof(string))
                    //        {
                    //            dr[j] = values[j];
                    //        }
                    //        else if (dt.Columns[j].DataType == typeof(System.Int64))
                    //        {
                    //            dr[j] = string.IsNullOrEmpty(values[j]) ? 0 : Convert.ToInt64(values[j]);
                    //        }
                    //        else if (dt.Columns[j].DataType == typeof(DateTime))
                    //        {
                    //            dr[j] = string.IsNullOrEmpty(values[j]) ? DateTime.Now : Convert.ToDateTime(values[j]);
                    //        }
                    //        else
                    //        {
                    //            dr[j] = values[j];
                    //        }

                    //    }
                    //    dt.Rows.Add(dr);
                    //}
                }
                if (dt.Rows.Count > 0)
                {
                    ds.Tables.Add(dt);
                }
            }
            return ds;
        }

        /// <summary>
        /// Formats the data set to string.
        /// </summary>
        /// <param name="param">The param.</param>
        /// <returns></returns>
        /// 作    者：zcnie
        /// 创建时间：2011-1-15
        public static string FormatDataSetToString(DataSet ds)
        {
            if (ds.IsEmpty())
                return string.Empty;

            StringBuilder sb = new StringBuilder();

            foreach (DataTable dt in ds.Tables)
            {
                sb.Append(dt.TableName);
                sb.Append("\u0001\u0001\u0004");

                foreach (DataColumn dc in dt.Columns)
                {
                    sb.Append(dc.ColumnName);
                    sb.Append("\u0001");
                }
                sb.Remove(sb.Length - 1, 1); //删除最后一个\u0001
                sb.Append("\u0002");

                foreach (DataRow dr in dt.Rows)
                {
                    for (int i = 0; i < dt.Columns.Count; i++)
                    {
                        sb.Append(dr[i].ToString());
                        sb.Append("\u0001");
                    }
                    sb.Remove(sb.Length - 1, 1);       //删除最后一个\u0001
                    sb.Append("\u0002");
                }
                sb.Append("\u0003");
            }

            return sb.ToString();
        }

        ///   <summary> 
        ///   将指定DataTable转换为其等效的Xml流形式。 
        ///   </summary> 
        ///   <param   name= "table "> DataTable对象 </param> 
        ///   <returns> </returns> 
        public static string ToXmlString(DataTable table)
        {
            if (table == null)
            {
                return null;
            }

            StringBuilder content = new StringBuilder();
            StringWriter writer = new StringWriter(content);
            XmlTextWriter xmlWriter = new XmlTextWriter(writer);

            try
            {
                DataSet ds = new DataSet();
                ds.Tables.Add(table.Copy());
                ds.WriteXml(xmlWriter);

                return content.ToString();
            }
            finally
            {
                writer.Close();
                xmlWriter.Close();
            }
        }

        /// <summary>
        /// 增加类型映射（NUMBER）。
        /// </summary>
        /// <param name="sType">类型映射名。</param>
        /// <returns></returns>
        /// 作者 : fli.chnet
        /// 修改时间 :2011-2-22 13:51
        public static Type GetFieldType(string sType)
        {
            Type ttype = null;
            if (sType == "DATE")
            {
                ttype = typeof(DateTime);
            }
            else if (sType == "ID" || sType == "NUMBER")
            {
                ttype = typeof(System.Int64);
            }
            else
            {
                ttype = typeof(string);
            }
            return ttype;
        }

        /// <summary>
        /// 将DataTable数据按前台接口通信格式封装。
        /// </summary>
        /// <param name="dt"></param>
        /// <returns></returns>
        public static string FormatDataTable(DataTable dt)
        {
            if (dt == null || dt.Rows.Count == 0) return null;
            StringBuilder result = new StringBuilder(dt.TableName + "\u0004");
            int count = 0;
            foreach (DataColumn dc in dt.Columns)
            {
                count++;
                //result += dc.ColumnName;
                result.Append(dc.ColumnName);
                if (count < dt.Columns.Count)           //列名之间加 "\u0001"
                {
                    //result += "\u0001";
                    result.Append("\u0001");
                }
                else if (dt.Rows.Count < 1)             //若无数据，在列名后加结束符。“\u0003"
                {
                    result.Append("\u0003");
                    return result.ToString();
                }
                else                                    //最后一列直接加“\u0002” 
                {
                    result.Append("\u0002");
                }
            }

            int rowCount = 0;
            foreach (DataRow dr in dt.Rows)
            {
                rowCount++;
                count = 0;
                foreach (DataColumn dc in dt.Columns)
                {
                    count++;
                    if (dc.DataType == typeof(DateTime) && dr[dc] != DBNull.Value)
                    {
                        if (DateTime.Compare(DateTime.MinValue, (DateTime)dr[dc]) == 0)
                        {
                            result.Append(string.Empty);
                        }
                        else
                        {
                            result.Append(((DateTime)dr[dc]).ToString("yyyy-MM-dd HH:mm:ss"));
                        }
                    }
                    else
                    {
                        result.Append(dr[dc]);
                    }
                    if (count < dt.Columns.Count)       //列值之间加"\u0001"
                    {
                        result.Append("\u0001");
                    }
                    else if (rowCount < dt.Rows.Count)  //一行遍历结束加"\u0002"
                    {
                        result.Append("\u0002");
                    }
                    else                                //最后一个结果集直接加"\u0003"
                    {
                        result.Append("\u0003");
                    }
                }
            }
            return result.ToString();
        }

        /// <summary>
        /// 方法说明：FormatSucessHead，格式化返回内容头。
        /// 作者    ：CHNET-LIFENG
        /// 创建时间：2007-5-9 13:50
        /// </summary>
        /// <param name="strReqID">The STR req ID.</param>
        /// <returns></returns>
        public static string FormatSucessHead(string strReqID)
        {
            return string.Format("{0}\u00010\u0001\u0005", strReqID);
        }
        /// <summary>
        /// 方法说明：FormatField,格式化返回值
        /// 作者    ：CHNET-LIFENG
        /// 创建时间：2007-5-9 13:49
        /// </summary>
        /// <param name="fieldsName">Name of the fields.</param>
        /// <param name="fields">The fields.</param>
        /// <returns></returns>
        public static string FormatField(string[] fieldsName, string[] fields)
        {
            //如果数组长度不一样，返回空
            if (!fieldsName.Length.Equals(fields.Length)) return null;
            string result = null;
            int count = 0;
            foreach (string fn in fieldsName)
            {
                if (string.IsNullOrEmpty(fn)) continue;
                result += string.Format("{0}\u0004{1}\u0002{2}\u0003", fn, fn, fields[count++]);
            }
            return result;
        }
        /// <summary>
        /// 方法说明：FormatFailInfo，格式化返回内容信息
        /// 作者    ：CHNET-LIFENG
        /// 创建时间：2007-5-9 13:50
        /// </summary>
        /// <param name="strReqID">The STR req ID.</param>
        /// <param name="errCode">The err code.</param>
        /// <param name="errMsg">The err MSG.</param>
        /// <returns></returns>
        public static string FormatFailInfo(string strReqID, long errCode, string errMsg)
        {
            return string.Format("{0}\u0001{1}\u0001{2}\u0005", strReqID, errCode.ToString(), errMsg);
        }
    }

        public class TableParams
    {
        public TableParams()
        {

        }
        public TableParams(string requestxml)
            : this()
        {
            EncodedXml = requestxml;
        }
        // XML格式如下：
        //<?xml version="1.0" encoding="UTF-8"?>
        //<PARAMS>
        //         <TABLES>
        //                   <TABLE NAME = "">
        //                            <FIELDS>
        //                                     <FIELD NAME = ""/>
        //                            </FIELDS>
        //                            <ROWS/>
        //                                     <ROW />
        //                            </ROWS>
        //                   </TABLE>
        //                   <TABLE NAME = "">
        //                            <FIELDS>
        //                                     <FIELD NAME = ""/>
        //                            </FIELDS>
        //                            <ROWS/>
        //                                     <ROW FIELDn = ""/>  此处的FIELDn为字段名称
        //                            </ROWS>
        //                   </TABLE>          
        //         </TABLES>
        //</PARAMS>
        public const string KEYLABLE = "ISKEY";
        public const string OPTYPE = "OPTYPE";
        public const string DBTABLENAME = "DBTABLENAME";
        public const string ID = "ID";

        public const string OLDKEY = "OLDKEY";

        private string encodedxml = null;

        /// <summary>
        /// 方法说明：Gets or sets the encoded XML.
        /// 作者    ：ZHOULX
        /// 创建时间：2009-4-20 10:19
        /// </summary>
        /// <value>The encoded XML.</value>
        public string EncodedXml
        {
            get
            {
                return encodedxml;
            }
            set
            {
                encodedxml = value;
                //if (!String.IsNullOrEmpty(encodedxml))
                //{
                //    //base64解码
                //    byte[] bytexml = CryptTools.Base64Decode(Encoding.UTF8.GetBytes(encodedxml));
                //    inputxml = Encoding.UTF8.GetString(bytexml);
                //}
            }
        }

        private string inputxml = null;

        /// <summary>
        /// 方法说明：Gets or sets the input XML.
        /// 作者    ：ZHOULX
        /// 创建时间：2009-4-20 9:44
        /// </summary>
        /// <value>The input XML.</value>
        public string InputXML
        {
            get
            {
                return inputxml;
            }
            set
            {
                inputxml = value;
            }
        }


    }
}
