namespace OraclePerfTest
{
    partial class Form1
    {
        /// <summary>
        /// 必需的设计器变量。
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// 清理所有正在使用的资源。
        /// </summary>
        /// <param name="disposing">如果应释放托管资源，为 true；否则为 false。</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows 窗体设计器生成的代码

        /// <summary>
        /// 设计器支持所需的方法 - 不要
        /// 使用代码编辑器修改此方法的内容。
        /// </summary>
        private void InitializeComponent()
        {
			this.groupBox1 = new System.Windows.Forms.GroupBox();
			this.cboxMulti = new System.Windows.Forms.CheckBox();
			this.btnClear = new System.Windows.Forms.Button();
			this.numericUpDown1 = new System.Windows.Forms.NumericUpDown();
			this.btnRun = new System.Windows.Forms.Button();
			this.lblSql = new System.Windows.Forms.Label();
			this.txtsql = new System.Windows.Forms.TextBox();
			this.splitContainer1 = new System.Windows.Forms.SplitContainer();
			this.rtxtCtosut = new System.Windows.Forms.RichTextBox();
			this.panel1 = new System.Windows.Forms.Panel();
			this.txtCtosut = new System.Windows.Forms.TextBox();
			this.comboBoxCtosut = new System.Windows.Forms.ComboBox();
			this.rtxtCtosdemo = new System.Windows.Forms.RichTextBox();
			this.panel2 = new System.Windows.Forms.Panel();
			this.txtCtosdemo = new System.Windows.Forms.TextBox();
			this.comboBoxCtosdemo = new System.Windows.Forms.ComboBox();
			this.progressBar1 = new System.Windows.Forms.ProgressBar();
			this.btnTosRun = new System.Windows.Forms.Button();
			this.groupBox1.SuspendLayout();
			((System.ComponentModel.ISupportInitialize)(this.numericUpDown1)).BeginInit();
			((System.ComponentModel.ISupportInitialize)(this.splitContainer1)).BeginInit();
			this.splitContainer1.Panel1.SuspendLayout();
			this.splitContainer1.Panel2.SuspendLayout();
			this.splitContainer1.SuspendLayout();
			this.panel1.SuspendLayout();
			this.panel2.SuspendLayout();
			this.SuspendLayout();
			// 
			// groupBox1
			// 
			this.groupBox1.Controls.Add(this.btnTosRun);
			this.groupBox1.Controls.Add(this.cboxMulti);
			this.groupBox1.Controls.Add(this.btnClear);
			this.groupBox1.Controls.Add(this.numericUpDown1);
			this.groupBox1.Controls.Add(this.btnRun);
			this.groupBox1.Controls.Add(this.lblSql);
			this.groupBox1.Controls.Add(this.txtsql);
			this.groupBox1.Dock = System.Windows.Forms.DockStyle.Top;
			this.groupBox1.Location = new System.Drawing.Point(0, 0);
			this.groupBox1.Name = "groupBox1";
			this.groupBox1.Size = new System.Drawing.Size(916, 129);
			this.groupBox1.TabIndex = 0;
			this.groupBox1.TabStop = false;
			this.groupBox1.Text = "Conditions";
			// 
			// cboxMulti
			// 
			this.cboxMulti.AutoSize = true;
			this.cboxMulti.Checked = true;
			this.cboxMulti.CheckState = System.Windows.Forms.CheckState.Checked;
			this.cboxMulti.Location = new System.Drawing.Point(728, 63);
			this.cboxMulti.Name = "cboxMulti";
			this.cboxMulti.Size = new System.Drawing.Size(82, 17);
			this.cboxMulti.TabIndex = 5;
			this.cboxMulti.Text = "MultiThread";
			this.cboxMulti.UseVisualStyleBackColor = true;
			// 
			// btnClear
			// 
			this.btnClear.Location = new System.Drawing.Point(6, 58);
			this.btnClear.Name = "btnClear";
			this.btnClear.Size = new System.Drawing.Size(49, 23);
			this.btnClear.TabIndex = 4;
			this.btnClear.Text = "cls";
			this.btnClear.UseVisualStyleBackColor = true;
			this.btnClear.Click += new System.EventHandler(this.btnClear_Click);
			// 
			// numericUpDown1
			// 
			this.numericUpDown1.Location = new System.Drawing.Point(728, 26);
			this.numericUpDown1.Name = "numericUpDown1";
			this.numericUpDown1.Size = new System.Drawing.Size(74, 20);
			this.numericUpDown1.TabIndex = 3;
			this.numericUpDown1.Value = new decimal(new int[] {
            5,
            0,
            0,
            0});
			// 
			// btnRun
			// 
			this.btnRun.Location = new System.Drawing.Point(728, 96);
			this.btnRun.Name = "btnRun";
			this.btnRun.Size = new System.Drawing.Size(75, 23);
			this.btnRun.TabIndex = 2;
			this.btnRun.Text = "Run";
			this.btnRun.UseVisualStyleBackColor = true;
			this.btnRun.Click += new System.EventHandler(this.btnRun_Click);
			// 
			// lblSql
			// 
			this.lblSql.AutoSize = true;
			this.lblSql.Location = new System.Drawing.Point(13, 26);
			this.lblSql.Name = "lblSql";
			this.lblSql.Size = new System.Drawing.Size(31, 13);
			this.lblSql.TabIndex = 1;
			this.lblSql.Text = "SQL:";
			// 
			// txtsql
			// 
			this.txtsql.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
            | System.Windows.Forms.AnchorStyles.Left)));
			this.txtsql.Location = new System.Drawing.Point(61, 20);
			this.txtsql.Multiline = true;
			this.txtsql.Name = "txtsql";
			this.txtsql.ScrollBars = System.Windows.Forms.ScrollBars.Both;
			this.txtsql.Size = new System.Drawing.Size(660, 103);
			this.txtsql.TabIndex = 0;
			// 
			// splitContainer1
			// 
			this.splitContainer1.Dock = System.Windows.Forms.DockStyle.Fill;
			this.splitContainer1.Location = new System.Drawing.Point(0, 129);
			this.splitContainer1.Name = "splitContainer1";
			// 
			// splitContainer1.Panel1
			// 
			this.splitContainer1.Panel1.Controls.Add(this.rtxtCtosut);
			this.splitContainer1.Panel1.Controls.Add(this.panel1);
			// 
			// splitContainer1.Panel2
			// 
			this.splitContainer1.Panel2.Controls.Add(this.rtxtCtosdemo);
			this.splitContainer1.Panel2.Controls.Add(this.panel2);
			this.splitContainer1.Size = new System.Drawing.Size(916, 404);
			this.splitContainer1.SplitterDistance = 454;
			this.splitContainer1.TabIndex = 1;
			// 
			// rtxtCtosut
			// 
			this.rtxtCtosut.Dock = System.Windows.Forms.DockStyle.Fill;
			this.rtxtCtosut.Location = new System.Drawing.Point(0, 43);
			this.rtxtCtosut.Name = "rtxtCtosut";
			this.rtxtCtosut.Size = new System.Drawing.Size(454, 361);
			this.rtxtCtosut.TabIndex = 10;
			this.rtxtCtosut.Text = "";
			// 
			// panel1
			// 
			this.panel1.Controls.Add(this.txtCtosut);
			this.panel1.Controls.Add(this.comboBoxCtosut);
			this.panel1.Dock = System.Windows.Forms.DockStyle.Top;
			this.panel1.Location = new System.Drawing.Point(0, 0);
			this.panel1.Name = "panel1";
			this.panel1.Size = new System.Drawing.Size(454, 43);
			this.panel1.TabIndex = 9;
			this.panel1.Paint += new System.Windows.Forms.PaintEventHandler(this.panel1_Paint);
			// 
			// txtCtosut
			// 
			this.txtCtosut.BackColor = System.Drawing.Color.Tan;
			this.txtCtosut.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(134)));
			this.txtCtosut.Location = new System.Drawing.Point(3, 3);
			this.txtCtosut.Multiline = true;
			this.txtCtosut.Name = "txtCtosut";
			this.txtCtosut.Size = new System.Drawing.Size(330, 30);
			this.txtCtosut.TabIndex = 1;
			this.txtCtosut.Text = "User Id=CTOS;Password=www;Data Source=CTOSUT ";
			// 
			// comboBoxCtosut
			// 
			this.comboBoxCtosut.FormattingEnabled = true;
			this.comboBoxCtosut.Items.AddRange(new object[] {
            "ODP",
            "ADO"});
			this.comboBoxCtosut.Location = new System.Drawing.Point(336, 12);
			this.comboBoxCtosut.Name = "comboBoxCtosut";
			this.comboBoxCtosut.Size = new System.Drawing.Size(84, 21);
			this.comboBoxCtosut.TabIndex = 7;
			// 
			// rtxtCtosdemo
			// 
			this.rtxtCtosdemo.Dock = System.Windows.Forms.DockStyle.Fill;
			this.rtxtCtosdemo.Location = new System.Drawing.Point(0, 43);
			this.rtxtCtosdemo.Name = "rtxtCtosdemo";
			this.rtxtCtosdemo.Size = new System.Drawing.Size(458, 361);
			this.rtxtCtosdemo.TabIndex = 10;
			this.rtxtCtosdemo.Text = "";
			// 
			// panel2
			// 
			this.panel2.Controls.Add(this.txtCtosdemo);
			this.panel2.Controls.Add(this.comboBoxCtosdemo);
			this.panel2.Dock = System.Windows.Forms.DockStyle.Top;
			this.panel2.Location = new System.Drawing.Point(0, 0);
			this.panel2.Name = "panel2";
			this.panel2.Size = new System.Drawing.Size(458, 43);
			this.panel2.TabIndex = 9;
			// 
			// txtCtosdemo
			// 
			this.txtCtosdemo.BackColor = System.Drawing.Color.Tan;
			this.txtCtosdemo.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(134)));
			this.txtCtosdemo.Location = new System.Drawing.Point(3, 3);
			this.txtCtosdemo.Multiline = true;
			this.txtCtosdemo.Name = "txtCtosdemo";
			this.txtCtosdemo.Size = new System.Drawing.Size(336, 30);
			this.txtCtosdemo.TabIndex = 2;
			this.txtCtosdemo.Text = "User Id=CTOS;Password=www;Data Source=CTOSDEMO";
			// 
			// comboBoxCtosdemo
			// 
			this.comboBoxCtosdemo.FormattingEnabled = true;
			this.comboBoxCtosdemo.Items.AddRange(new object[] {
            "ODP",
            "ADO"});
			this.comboBoxCtosdemo.Location = new System.Drawing.Point(345, 12);
			this.comboBoxCtosdemo.Name = "comboBoxCtosdemo";
			this.comboBoxCtosdemo.Size = new System.Drawing.Size(84, 21);
			this.comboBoxCtosdemo.TabIndex = 8;
			// 
			// progressBar1
			// 
			this.progressBar1.Dock = System.Windows.Forms.DockStyle.Bottom;
			this.progressBar1.Location = new System.Drawing.Point(0, 510);
			this.progressBar1.Name = "progressBar1";
			this.progressBar1.Size = new System.Drawing.Size(916, 23);
			this.progressBar1.TabIndex = 4;
			// 
			// btnTosRun
			// 
			this.btnTosRun.Location = new System.Drawing.Point(812, 96);
			this.btnTosRun.Name = "btnTosRun";
			this.btnTosRun.Size = new System.Drawing.Size(75, 23);
			this.btnTosRun.TabIndex = 6;
			this.btnTosRun.Text = "TOS-Run";
			this.btnTosRun.UseVisualStyleBackColor = true;
			this.btnTosRun.Click += new System.EventHandler(this.btnTosRun_Click);
			// 
			// Form1
			// 
			this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
			this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
			this.ClientSize = new System.Drawing.Size(916, 533);
			this.Controls.Add(this.progressBar1);
			this.Controls.Add(this.splitContainer1);
			this.Controls.Add(this.groupBox1);
			this.Name = "Form1";
			this.Text = "DbPerfTest";
			this.Load += new System.EventHandler(this.Form1_Load);
			this.groupBox1.ResumeLayout(false);
			this.groupBox1.PerformLayout();
			((System.ComponentModel.ISupportInitialize)(this.numericUpDown1)).EndInit();
			this.splitContainer1.Panel1.ResumeLayout(false);
			this.splitContainer1.Panel2.ResumeLayout(false);
			((System.ComponentModel.ISupportInitialize)(this.splitContainer1)).EndInit();
			this.splitContainer1.ResumeLayout(false);
			this.panel1.ResumeLayout(false);
			this.panel1.PerformLayout();
			this.panel2.ResumeLayout(false);
			this.panel2.PerformLayout();
			this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.GroupBox groupBox1;
        private System.Windows.Forms.Label lblSql;
        private System.Windows.Forms.TextBox txtsql;
        private System.Windows.Forms.SplitContainer splitContainer1;
        private System.Windows.Forms.TextBox txtCtosut;
        private System.Windows.Forms.TextBox txtCtosdemo;
        private System.Windows.Forms.Button btnRun;
        private System.Windows.Forms.NumericUpDown numericUpDown1;
        private System.Windows.Forms.Button btnClear;
        private System.Windows.Forms.ProgressBar progressBar1;
        private System.Windows.Forms.CheckBox cboxMulti;
        private System.Windows.Forms.Panel panel1;
        private System.Windows.Forms.ComboBox comboBoxCtosut;
        private System.Windows.Forms.RichTextBox rtxtCtosut;
        private System.Windows.Forms.RichTextBox rtxtCtosdemo;
        private System.Windows.Forms.Panel panel2;
        private System.Windows.Forms.ComboBox comboBoxCtosdemo;
		private System.Windows.Forms.Button btnTosRun;
    }
}

