using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;
using Oracle.DataAccess.Client;
using System.Diagnostics;
using CM.CTOS.Common.Data;
using System.Web;
using System.Web.Hosting;
using System.IO;
using System.Configuration;

namespace OraclePerfTest
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            
            InitializeComponent();

            
        }

        private void btnRun_Click(object sender, EventArgs e)
        {
            this.btnRun.Enabled = false;
            this.Cursor = Cursors.WaitCursor;

            this.progressBar1.Value = 0;


            var runtimes = (int)this.numericUpDown1.Value;
            this.progressBar1.Maximum = runtimes * 2 ;
            this.progressBar1.Minimum = 0;
            this.progressBar1.Step = 1;

            

            string splitter = string.Format("------------ {0} -------------{1}", DateTime.Now, Environment.NewLine);
            this.rtxtCtosut.Text += splitter;
            this.rtxtCtosdemo.Text += splitter;
            

            
            bool isMulti = this.cboxMulti.Checked;

            this.progressBar1.PerformStep();

            DbModel dbmodel1 = new DbModel { Conn = this.txtCtosut.Text, isOdp = this.comboBoxCtosut.SelectedIndex == 0, TextBoxName = "rtxtCtosut" };
            DbModel dbmodel2 = new DbModel { Conn = this.txtCtosdemo.Text, isOdp = this.comboBoxCtosdemo.SelectedIndex == 0, TextBoxName = "rtxtCtosdemo" };


            while (runtimes-- > 0)
            {
                if (isMulti)
                {
                    ThreadPool.QueueUserWorkItem(RunSQl, dbmodel1);
                    ThreadPool.QueueUserWorkItem(RunSQl, dbmodel2);
                }
                else
                {
                    RunSQl(dbmodel1);
                    RunSQl(dbmodel2);
                }

            }
        }



        public void RunSQl(object dbmodel)
        {
            DbModel model = (DbModel)dbmodel;

            if (string.IsNullOrEmpty(model.Conn)) return;
            string sql = this.txtsql.Text.Trim();
            string conn = model.Conn;

            RichTextBox ctl = null;
            if (model.TextBoxName == "rtxtCtosut")
            {
                ctl = this.rtxtCtosut;
            }
            else
            {
                ctl = this.rtxtCtosdemo;                
            }

            Stopwatch  sw = Stopwatch.StartNew();
            sw.Restart();
            StringBuilder sb = new StringBuilder();
            DataSet ds = new DataSet();
            string dbname = null;
            try
            {
                if (model.isOdp)
                {
                    using (OracleConnection oraconn = new OracleConnection(conn))
                    {
                        oraconn.Open();
                        dbname = oraconn.ServiceName;
                        OracleCommand cmd = new OracleCommand(sql, oraconn);
                        OracleDataAdapter oda = new OracleDataAdapter(cmd);
                        int x = oda.Fill(ds);
                        sw.Stop();
                    }
                }
                else
                {
                    using (System.Data.OracleClient.OracleConnection oraconn = new System.Data.OracleClient.OracleConnection(conn))
                    {
                        oraconn.Open();
                        dbname = oraconn.DataSource;
                        System.Data.OracleClient.OracleCommand cmd = new System.Data.OracleClient.OracleCommand(sql, oraconn);
                        System.Data.OracleClient.OracleDataAdapter oda = new System.Data.OracleClient.OracleDataAdapter(cmd);
                        int x = oda.Fill(ds);
                        sw.Stop();
                    }
                }
                DataTable dt = ds.Tables[0];
                sb.AppendFormat("#{3}, server: {4}, Return {0} rows with {1} columns, cost: {2}ms", dt.Rows.Count, dt.Columns.Count, sw.ElapsedMilliseconds, Thread.CurrentThread.ManagedThreadId, dbname);
                if (dt.Rows.Count == 1)
                {
                    sb.AppendLine();
                    foreach (DataColumn dc in dt.Columns)
                    {                        
                        sb.AppendFormat("Column {0}; Type {1}; Value {2};", dc.ColumnName.PadLeft(20), dc.DataType.ToString().PadLeft(20), dt.Rows[0][dc].ToString().PadLeft(20));
                        sb.AppendLine();
                    }
                }
            }
            catch (Exception ex)
            {
                sb.AppendFormat("Error: {0}" , ex.Message);
            }
            
            sb.AppendLine();



                if (this.rtxtCtosut.InvokeRequired)
                {
                    Invoke(new SetContrlValueDelegate(SetTextboxValue), ctl, sb.ToString());
                }
                else
                {
                    ctl.Text += sb.ToString();
                    SetState();
                }

        }

        delegate void SetContrlValueDelegate(Control txtbox, object s);
        void SetTextboxValue(Control ctl , object sb)
        {
            if (ctl is RichTextBox)
            {
                RichTextBox txt = (RichTextBox)ctl;
                txt.Text += sb.ToString();
            }
            SetState();
        }

        private void SetState()
        {

            if (this.progressBar1.Value >= this.progressBar1.Maximum)
            {
                this.btnRun.Enabled = true;
                this.Cursor = Cursors.Default;
                //this.progressBar1.Value = 0;
            }
            else
            {
                this.progressBar1.PerformStep();
            }
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            this.comboBoxCtosdemo.SelectedIndex = this.comboBoxCtosut.SelectedIndex = 0;
            this.numericUpDown1.Value = 1;

            

            #region
            this.txtsql.Text = @"SELECT DISTINCT VESSELBERTHPLAN.OUTBOUNDVOY,
                VESSELBERTHPLAN.INBOUNDVOY,
                VESSELBERTHPLAN.STATE,
                VESSELBERTHPLAN.TRADETYPE,
                VESSELBERTHPLAN.VESSELBERTHPLANNO,
                VESSELBERTHPLAN.LINEOPERATOR,
                VESSELBERTHPLAN.CLOSETIME,
                VESSELBERTHPLAN.ATB,
                VESSELBERTHPLAN.ATA,
                VESSELBERTHPLAN.ETB,
                VESSELBERTHPLAN.ETD
FROM   VESSELBERTHPLAN
INNER  JOIN VESSELINFO
ON     VESSELBERTHPLAN.EVESSELNAME = VESSELINFO.EVESSELNAME
WHERE  1 = 1
and rownum < 2";

			this.txtCtosut.Text = ConfigurationManager.ConnectionStrings["OracleConnection"].ToString();
			this.txtCtosdemo.Text = ConfigurationManager.ConnectionStrings["OracleConnection"].ToString();

            #endregion
        }

        private void btnClear_Click(object sender, EventArgs e)
        {
            this.txtsql.Text = string.Empty;
        }

        private void panel1_Paint(object sender, PaintEventArgs e)
        {

        }

		private void btnTosRun_Click(object sender, EventArgs e)
		{
			var ret = MessageBox.Show(ConfigurationManager.ConnectionStrings["OracleConnection"].ToString(), "确认数据库连接", MessageBoxButtons.YesNo);
			if (ret != System.Windows.Forms.DialogResult.Yes) return;
			try
			{
				CM.TOS.V4.Common.TOSFramework.Start();
				Thread.GetDomain().SetData(".appPath", @"c:\Test");
				Thread.GetDomain().SetData(".appVPath", "/");
				TextWriter tw = new StringWriter();
				string address = "http://www.cmhit.com/";
				HttpWorkerRequest wr = new TestWorkerRequest("login.aspx", null, tw, address);
				HttpContext.Current = new HttpContext(wr);

				// 构造 HTTP 调用上下文对象
				HttpContext ctxt = new HttpContext(wr);


				using (CM.CTOS.Common.Core.TosRuntimeContext context = CM.CTOS.Common.Core.TosSessionRuntime.InitializeRuntime(
					CM.CTOS.Utility.LoginUserInfo.TicketIDInitValue,
					"SQL",
					Guid.NewGuid().ToString(),
					"127.0.0.1"))
				{
					using (CM.CTOS.Common.Data.IPsersistenceToken token = CM.CTOS.Common.Core.TosSessionRuntime.CurrentContext.PreparePersistContext())
					{
						CommonDataAccess cda = new CommonDataAccess();
						var db = cda.db;
						var cmd = db.GetSqlStringCommand(this.txtsql.Text);
						int i = cda.TosExecuteNonQuery(cmd);

						token.EndTransaction();

						MessageBox.Show("OK - 执行成功行数：" + i.ToString());
					}
				}
			}
			catch (Exception ex)
			{
				MessageBox.Show(ex.Message);
			}
			finally
			{
				CM.TOS.V4.Common.TOSFramework.End();
			}
		}


    }

    public class DbModel
    {
        public string Conn { get; set; }
        public string TextBoxName { get; set; }
        public bool isOdp { get; set; }
    }


}


