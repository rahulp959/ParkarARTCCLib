							<div class="nav">
								<div class="nav-l"></div>
								<div class="nav-r"></div>
								<div class="nav-center">
									<ul class="menu">
										<li><a href="/"><span class="l"> </span><span class="r"> </span><span class="t">Home</span></a>
										</li>
                                        <li><a href="#"><span class="l"> </span><span class="r"> </span><span class="t">Pilots</span></a>
                                        	<ul>
                                            	<li><a href="http://aircharts.org/charts.php"><span class="l"> </span><span class="r"> </span><span class="t">Charts</span></a></li>
                                                <li><a href="/?page=feedback"><span class="l"> </span><span class="r"> </span><span class="t">Feedback</span></a></li>
                                                <li><a href="/?page=routes"><span class="l"> </span><span class="r"> </span><span class="t">Preferred Routes</span></a></li>
                                            </ul>
										</li>
                                        <li><a href="#"><span class="l"> </span><span class="r"> </span><span class="t">Controllers</span></a>
                                        	<ul>
                                            	<li><a href="/?page=roster"><span class="l"> </span><span class="r"> </span><span class="t">Roster</span></a></li>
                                                <li><a href="http://artcc.aircharts.org/zob.php"><span class="l"> </span><span class="r"> </span><span class="t">TMU Display</span></a></li>
                                                <li><a href="/?page=downloads"><span class="l"> </span><span class="r"> </span><span class="t">Downloads</span></a></li>
                                                <!--<li><a href="http://awts.aircharts.org/?facility=ZOB"><span class="l"> </span><span class="r"> </span><span class="t">Training System</span></a></li>-->
                                                <li><a href="/?page=sops"><span class="l"> </span><span class="r"> </span><span class="t">SOPs / LOAs</span></a></li>
                                                <!--<li><a href="/?page=vcapp"><span class="l"> </span><span class="r"> </span><span class="t">Visiting Application</span></a></li>-->
                                                <li><a href="/?page=feedbackarchive"><span class="l"> </span><span class="r"> </span><span class="t">Feedback Archive</span></a></li>
                                                <li><a href="/?page=stats"><span class="l"> </span><span class="r"> </span><span class="t">Statistics</span></a></li>
                                            </ul>
										</li>
                                        <li><a href="#"><span class="l"> </span><span class="r"> </span><span class="t">Feedback</span></a>
                                        	<ul>
                                            	<li><a href="/?page=feedback"><span class="l"> </span><span class="r"> </span><span class="t">Leave Feedback</span></a></li>
                                                <li><a href="/?page=feedbackarchive"><span class="l"> </span><span class="r"> </span><span class="t">Feedback Archive</span></a></li>
                                            </ul>
										</li>
                                        <li><a href="/smf"><span class="l"> </span><span class="r"> </span><span class="t">Forums</span></a></li>
                                        <?php if($_SESSION['access'] > 0) { ?> <li><a href="/?page=details"><span class="l"> </span><span class="r"> </span><span class="t">Change Details</span></a><?php } ?>
										</li>
                                        <?php if($_SESSION['access'] > 24) { ?> <li><a href="#"><span class="l"> </span><span class="r"> </span><span class="t">Admin</span></a>
                                        	<ul>
                                            	<?php if($_SESSION['access'] > 29) { ?> <li><a href="/?page=addcon"><span class="l"> </span><span class="r"> </span><span class="t">Add Controller</span></a></li><?php } ?>
                                                <?php if($_SESSION['access'] > 24) { ?> <li><a href="/?page=eventadm"><span class="l"> </span><span class="r"> </span><span class="t">Events Admin</span></a></li><?php } ?>
                                                <?php if($_SESSION['access'] > 24) { ?> <li><a href="/?page=newsadm"><span class="l"> </span><span class="r"> </span><span class="t">News Admin</span></a></li><?php } ?>
                                                <?php if($_SESSION['access'] > 24) { ?> <li><a href="/?page=fileadm"><span class="l"> </span><span class="r"> </span><span class="t">Files Admin</span></a></li><?php } ?>
                                         <?php } ?>
										</li>
                                        <?php if($_SESSION['access'] > 0) { ?> <li><a href="/?page=logout"><span class="l"> </span><span class="r"> </span><span class="t">Logout</span></a></li><?php } ?>
									</ul>
								</div>
							</div>