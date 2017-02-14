<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Error</title>
    <style media="screen">
      * {
        box-sizing: border-box;
      }
      body {
        margin: 0;
        padding: 0;
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
      }
      header {
        padding: 24px;
        box-shadow: 0 3px 3px rgba(0, 0, 0, 0.3);
      }

      header .header_info {
        font-size: 1.1rem;
      }

      header h1 {
        margin-bottom: 0
      }

      header em {
        color: #F44336;
        font-weight: bold;
        font-style: normal;
      }

      .page__wrapper {
        padding: 24px;
        max-width: 800px;
      }

      .panel {
        border: 1px solid rgba(0,0,0,0.5);
        padding: 12px 24px;
      }


      .traceback_line {
        padding: 16px;
        border-bottom: 1px solid #e0e0e0;
        cursor: pointer;
      }

      .traceback_line:first-of-type {
          border-top: 1px solid #e0e0e0;
      }

      .traceback_function_info {
        font-size: 1.1rem;
      }

      .traceback_file_info {
        font-size: 1rem;
        margin-top: 5px;
      }

      .traceback_line .class {
        font-weight: 900;
      }

    </style>
  </head>
  <body>
    <header>
      <div class="header_info">
        <em><?php echo 'Page Not Found' ?></em>
        at:
        <b><?php echo $path; ?></b>
      </div>
    </header>
    <div class="page__wrapper">
      <div class="panel">
        <table>
          <thead>
            <tr>
              <th>Method</th>
              <th>Path</th>
              <th>Controller#Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach(Application::get_router()->routes as $route): ?>
              <tr>
                <td><?php echo implode(', ', $route->via) ?></td>
                <td><?php echo $route->raw_path ?></td>
                <td><?php echo $route->controller . '#' . $route->action ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="panel">
        SOURCE CODE
      </div>

      <div class="panel">
        <h2>Request Info</h2>
        <b>Params:</b>
        <?php echo implode(', ', $env->request_vars); ?>
      </div>
    </div>



  </body>
</html>
