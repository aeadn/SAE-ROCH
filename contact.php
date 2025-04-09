<!DOCTYPE HTML>
<html>
	<head>
		<title>Contact - Alpha by HTML5 UP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload">
		<div id="page-wrapper">

			<!-- Header -->
			<?php include('header.php'); ?>

			<!-- Main -->
			<section id="main" class="container medium">
				<header>
					<h2>Contactez moi !</h2>
					<p>Besoin d'un renseigment? N'hésitez pas.</p>
				</header>
				<div class="box">
					<?php
						if ($_SERVER["REQUEST_METHOD"] == "POST") {
							// Récupérer les données du formulaire
							$name = htmlspecialchars($_POST['name']);
							$email = htmlspecialchars($_POST['email']);
							$subject = htmlspecialchars($_POST['subject']);
							$message = htmlspecialchars($_POST['message']);
							
							// Adresse email de destination
							$to = "abby.baudnazebi@gmail.com";

							// Sujet de l'email
							$subjectEmail = "Message from: " . $name . " - " . $subject;

							// Contenu de l'email
							$body = "Name: " . $name . "\n" . 
								"Email: " . $email . "\n" .
								"Message: \n" . $message;

							// En-têtes de l'email
							$headers = "From: " . $email . "\r\n" . 
								"Reply-To: " . $email . "\r\n" .
								"Content-Type: text/plain; charset=UTF-8";

							// Envoi de l'email
							if (mail($to, $subjectEmail, $body, $headers)) {
								echo "<p style='color: green;'>Votre message a été envoyé avec succès !</p>";
								// Réinitialiser les champs du formulaire après l'envoi
								$_POST = [];
							} else {
								echo "<p style='color: red;'>Il y a eu une erreur lors de l'envoi de votre message. Veuillez réessayer plus tard.</p>";
							}
						}

					?>
					<form method="post" action="#">
						<div class="row gtr-50 gtr-uniform">
							<div class="col-6 col-12-mobilep">
								<input type="text" name="name" id="name" value="" placeholder="Nom" required />
							</div>
							<div class="col-6 col-12-mobilep">
								<input type="email" name="email" id="email" value="" placeholder="Email" required />
							</div>
							<div class="col-12">
								<input type="text" name="subject" id="subject" value="" placeholder="Objet" required />
							</div>
							<div class="col-12">
								<textarea name="message" id="message" placeholder="Saisissez votre message..." rows="6" required></textarea>
							</div>
							<div class="col-12">
								<ul class="actions special">
									<li><input type="submit" value="Envoyer" /></li>
								</ul>
							</div>
						</div>
					</form>
				</div>
			</section>

			<!-- Footer -->
			<?php include('footer.php'); ?>

		</div>
	</body>
</html>
