<!-- NAVBAR -->
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href=".">Energy Project</a>
          <ul class="nav">
            <li<?php if ($page_file == "index") echo " class=active" ?>><a href=".">Home</a></li>
            <li<?php if ($page_file == "daily-averages") echo " class=active" ?>><a href="daily-averages">Daily Averages</a></li>
            <li<?php if ($page_file == "weekly-averages") echo " class=active" ?>><a href="weekly-averages">Weekly Averages</a></li>
            <li<?php if ($page_file == "temperature") echo " class=active" ?>><a href="web-design">Temperature</a></li>
          </ul>
        </div>
      </div>
    </div>
