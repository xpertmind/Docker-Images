# Configuration file for jupyter-server.

c = get_config()  #noqa

c.IdentityProvider.token=''
c.NotebookApp.ip = '*'
c.NotebookApp.password = ''
c.NotebookApp.open_browser = False
c.NotebookApp.token = ''