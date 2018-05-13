        </div>
  </div>
    <div class="col-xs-4">
      <aside id="chat">
        <div id="chat_messages">
          <div id="options">
          </div>
        </div>
        <div id="chat_form">
          <form action="#" method="post" name="chat_write" id="chat_write" class="form-inline">
            <div class="input-group">
              <input type="text" name="message" id="message" placeholder="Enter chat message . . . " class="form-control" />
            </div><!--/.input-group-->
      <button type="submit" class="btn btn-default">Say</button>
    </form>
              </div><!--/.chat_form-->
            </aside><!--/#chat-->
          </div><!--/.col-xs-4-->
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <footer>&copy; 03/25/2014 - <?php echo date("m/d/Y", time()); ?></footer>
          </div>
        </div>
      </div>
    </div><!-- /.container -->
  </body>
</html>
<?php
ob_flush();
