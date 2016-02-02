using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Web.Hosting;

namespace OraclePerfTest
{
	public class TestWorkerRequest : SimpleWorkerRequest
	{
		private readonly string hostName = "";

		/// <summary>
		/// Initializes a new instance of the <see cref="T:System.Web.Hosting.SimpleWorkerRequest"/> class when the target application domain has been created using the <see cref="M:System.Web.Hosting.ApplicationHost.CreateApplicationHost(System.Type,System.String,System.String)"/> method.
		/// </summary>
		/// <param name="page">The page to be requested (or the virtual path to the page, relative to the application directory).</param>
		/// <param name="query">The text of the query string.</param>
		/// <param name="output">A <see cref="T:System.IO.TextWriter"/> that captures output from the response</param>
		/// <param name="hostName">The host name that will be requested.</param>
		public TestWorkerRequest(string page, string query, TextWriter output, string hostName)
			: base(page, query, output)
		{
			this.hostName = hostName;
		}

		/// <summary>
		/// Initializes a new instance of the <see cref="T:System.Web.Hosting.SimpleWorkerRequest"/> class for use in an arbitrary application domain, when the user code creates an <see cref="T:System.Web.HttpContext"/> (passing the SimpleWorkerRequest as an argument to the HttpContext constructor).
		/// </summary>
		/// <param name="appVirtualDir">The virtual path to the application directory; for example, "/app".</param>
		/// <param name="appPhysicalDir">The physical path to the application directory; for example, "c:\app".</param>
		/// <param name="page">The virtual path for the request (relative to the application directory).</param>
		/// <param name="query">The text of the query string.</param>
		/// <param name="output">A <see cref="T:System.IO.TextWriter"/> that captures the output from the response.</param>
		/// <param name="hostName">The host name that will be requested.</param>
		/// <exception cref="T:System.Web.HttpException">The <paramref name="appVirtualDir"/> parameter cannot be overridden in this context.
		/// </exception>
		public TestWorkerRequest(string appVirtualDir, string appPhysicalDir, string page, string query, TextWriter output, string hostName)
			: base(appVirtualDir, appPhysicalDir, page, query, output)
		{
			this.hostName = hostName;
		}

		/// <summary>
		/// Returns the server IP address of the interface on which the request was received.
		/// </summary>
		/// <returns>
		/// The server IP address of the interface on which the request was received.
		/// </returns>
		public override string GetLocalAddress()
		{
			return hostName;
		}
	}
}
