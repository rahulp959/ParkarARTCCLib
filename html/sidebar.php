													</div>
													<div class="cleared"></div>
												</div>
												<div class="cleared"></div>
											</div>
										</div>
										<div class="cleared"></div>
									</div>
									<div class="layout-cell sidebar1">
										<div class="block">
											<div class="block-body">
												<div class="blockheader">
													<div class="l"></div>
													<div class="r"></div>
													<h3 class="t">Login</h3>
												</div>
												<div class="blockcontent">
													<div class="blockcontent-body">
                                                    	<?php
														session_start();
														if($_SESSION['cid'] == "")
														{
															if (isset($_COOKIE['login']) && $_REQUEST['page'] != "logout")
															{
																$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
																$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
																
																$test = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, 518856462569894564, $_COOKIE['login'], MCRYPT_MODE_ECB, $iv);
																$test = explode("-", $test);
																$login = $ZOB->check_auth($test[0], trim(stripslashes($test[1])), 0);
																if ($login == 1)
																{
																	echo $_SESSION['fname'] . " " . $_SESSION['lname'] . " you are logged into the ZOB website, welcome aboard";
																}
															}
															?>
															<form action="./login.php" method="post">
                                                        	<label for="CID">CID : </label>
                                                        	<input type="text" name="CID" maxlength="7" size="10" />
                                                        	<br />
                                                        	<label for="password">Password :</label>
                                                        	<input type="password" name="pass" />
                                                            <label for="cookie">Stay Logged in?</label>
                                                            <input type="checkbox" name="cookie" value="1" />
                                                        	<br />
                                                        	<span class="button-wrapper"><span class="button-l"> </span><span class="button-r"> </span><input class="button" type="submit" value="Login" />
                                                        	</form>
                                                            <?php
														} else {
															echo $_SESSION['fname'] . " " . $_SESSION['lname'] . " you are logged into the ZOB website, welcome aboard";
														}
															?>
														<div class="cleared"></div>
													</div>
												</div>
												<div class="cleared"></div>
											</div>
										</div>
										<div class="block">
											<div class="block-body">
												<div class="blockheader">
													<div class="l"></div>
													<div class="r"></div>
													<h3 class="t">Latest News</h3>
												</div>
												<div class="blockcontent">
													<div class="blockcontent-body">
														<div>
															<? $ZOB->grab_news_sidebar(5); ?>
														</div>
														<div class="cleared"></div>
													</div>
												</div>
												<div class="cleared"></div>
											</div>
										</div>
										<div class="block">
											<div class="block-body">
												<div class="blockheader">
													<div class="l"></div>
													<div class="r"></div>
													<h3 class="t">Events</h3>
												</div>
												<div class="blockcontent">
													<div class="blockcontent-body">
														<div>
															<? $ZOB->grab_events_sidebar(5); ?>
														</div>
														<div class="cleared"></div>
													</div>
												</div>
												<div class="cleared"></div>
											</div>
										</div>
										<div class="block">
											<div class="block-body">
												<div class="blockheader">
													<div class="l"></div>
													<div class="r"></div>
													<h3 class="t">Pilots Online</h3>
												</div>
												<div class="blockcontent">
													<div class="blockcontent-body">
														<div>
															<? echo $ZOB->grab_pilots_online(); ?>
														</div>
														<div class="cleared"></div>
													</div>
												</div>
												<div class="cleared"></div>
											</div>
										</div>
										<div class="block">
											<div class="block-body">
												<div class="blockheader">
													<div class="l"></div>
													<div class="r"></div>
													<h3 class="t">ATC Online</h3>
												</div>
												<div class="blockcontent">
													<div class="blockcontent-body">
														<div>
															<? echo $ZOB->grab_controllers_online(); ?>
														</div>
														<div class="cleared"></div>
													</div>
												</div>
												<div class="cleared"></div>
											</div>
										</div>
										<div class="cleared"></div>
									</div>
								</div>
							</div>
							<div class="cleared"></div>