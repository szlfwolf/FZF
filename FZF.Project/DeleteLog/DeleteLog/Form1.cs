using System;
using System.ComponentModel;
using System.Drawing;
using System.IO;
using System.Windows.Forms;
namespace DeleteLog
{
	public class Form1 : Form
	{
		private IContainer components = null;
		private DateTimePicker dateTimePicker1;
		private Label label1;
		private Label label2;
		private Button button1;
		private FolderBrowserDialog folderBrowserDialog1;
		private CheckBox checkBox1;
		public Form1()
		{
			this.InitializeComponent();
		}
		private void button1_Click(object sender, EventArgs e)
		{
			string text = string.Empty;
			if (this.checkBox1.Checked)
			{
				text = Application.StartupPath;
			}
			else
			{
				DialogResult dialogResult = this.folderBrowserDialog1.ShowDialog();
				if (dialogResult == DialogResult.OK)
				{
					text = this.folderBrowserDialog1.SelectedPath;
				}
			}
			if (!string.IsNullOrEmpty(text))
			{
				DateTime dateTime = DateTime.Parse(this.dateTimePicker1.Value.ToShortDateString());
				if (dateTime >= DateTime.Now)
				{
					MessageBox.Show("仅能清理当前日期以前日志...");
				}
				else
				{
					string text2 = string.Concat(new string[]
					{
						"确定要删除目录（含子目录）：",
						text,
						"下修改日期在：",
						dateTime.ToShortDateString(),
						"前的所有日志文件?"
					});
					string caption = "确认";
					MessageBoxButtons buttons = MessageBoxButtons.OKCancel;
					DialogResult dialogResult2 = MessageBox.Show(this, text2, caption, buttons, MessageBoxIcon.Question, MessageBoxDefaultButton.Button2);
					if (dialogResult2 == DialogResult.Cancel)
					{
						base.Close();
					}
					string[] files = Directory.GetFiles(text, "log.*", SearchOption.AllDirectories);
					int num = 0;
					string[] array = files;
					for (int i = 0; i < array.Length; i++)
					{
						string text3 = array[i];
						FileInfo fileInfo = new FileInfo(text3);
						if (!(fileInfo.LastWriteTime >= dateTime))
						{
							File.Delete(text3);
							num++;
						}
					}
					MessageBox.Show(string.Concat(new object[]
					{
						"清理完毕，共清理：",
						num,
						"个修改日期在",
						dateTime.ToShortDateString(),
						"前的文件."
					}));
				}
			}
		}
		protected override void Dispose(bool disposing)
		{
			if (disposing && this.components != null)
			{
				this.components.Dispose();
			}
			base.Dispose(disposing);
		}
		private void InitializeComponent()
		{
			ComponentResourceManager componentResourceManager = new ComponentResourceManager(typeof(Form1));
			this.dateTimePicker1 = new DateTimePicker();
			this.label1 = new Label();
			this.label2 = new Label();
			this.button1 = new Button();
			this.folderBrowserDialog1 = new FolderBrowserDialog();
			this.checkBox1 = new CheckBox();
			base.SuspendLayout();
			this.dateTimePicker1.Location = new Point(68, 26);
			this.dateTimePicker1.Name = "dateTimePicker1";
			this.dateTimePicker1.Size = new Size(107, 21);
			this.dateTimePicker1.TabIndex = 0;
			this.label1.AutoSize = true;
			this.label1.Location = new Point(181, 30);
			this.label1.Name = "label1";
			this.label1.Size = new Size(29, 12);
			this.label1.TabIndex = 1;
			this.label1.Text = "日前";
			this.label2.AutoSize = true;
			this.label2.Location = new Point(33, 30);
			this.label2.Name = "label2";
			this.label2.Size = new Size(29, 12);
			this.label2.TabIndex = 2;
			this.label2.Text = "清理";
			this.button1.Location = new Point(35, 91);
			this.button1.Name = "button1";
			this.button1.Size = new Size(175, 29);
			this.button1.TabIndex = 3;
			this.button1.Text = "清理";
			this.button1.UseVisualStyleBackColor = true;
			this.button1.Click += new EventHandler(this.button1_Click);
			this.checkBox1.AutoSize = true;
			this.checkBox1.Location = new Point(38, 62);
			this.checkBox1.Name = "checkBox1";
			this.checkBox1.Size = new Size(72, 16);
			this.checkBox1.TabIndex = 4;
			this.checkBox1.Text = "当前目录";
			this.checkBox1.UseVisualStyleBackColor = true;
			base.AutoScaleDimensions = new SizeF(6f, 12f);
			base.AutoScaleMode = AutoScaleMode.Font;
			base.ClientSize = new Size(279, 215);
			base.Controls.Add(this.checkBox1);
			base.Controls.Add(this.button1);
			base.Controls.Add(this.label2);
			base.Controls.Add(this.label1);
			base.Controls.Add(this.dateTimePicker1);
			base.FormBorderStyle = FormBorderStyle.FixedToolWindow;
			base.Icon = (Icon)componentResourceManager.GetObject("$this.Icon");
			base.Name = "Form1";
			this.Text = "清理日志";
			base.ResumeLayout(false);
			base.PerformLayout();
		}
	}
}
