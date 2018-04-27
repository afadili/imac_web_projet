<!DOCTYPE html>
<html>
<head>
	<title>Hello</title>
	<style type="text/css" media="screen">
		body{
			font-family: sans-serif;
		}
	</style>
</head>
<body>
	<header id="header" class="">
		<h1>Emoji-tracker API</h1>	
		<p>Back-end api for <i>Emoji-tracker</i> a analytics tool for emoji usage on tweeter.</p>
		<p>Part of university project in web developpement: <a href="https://www.ingenieur-imac.fr">IMAC</a>/<a href="https://www.u-pem.fr">UPEM</a></p>
		<a href="http://server.piergabory.com:5000/">Emoji-tracker App</a>
		<hr>
	</header><!-- /header -->
	
	<main>
		<h2>Endpoints</h2>
		<pre>
			/
			/api
				/ranking/emojis
					/by_{method} [usage, average_retweets, average_favorites, average_popularity, average_responses] 
					/by_{method}/limit_to_{number}	#todo
					/by_{method}/since_{date}/limit_to_{number} #todo
					/by_{method}/between_{date}_and_{date}/limit_to_{number} #todo

				/emoji
					/ 			 
					/characters		#all emojis characters in an array
					/search/{needle}  	 
					/U+{code}		 
					/bymood/{mood}   
					/byhashtag/{word}

				/statistics
					/for_U+{code}
					/for_U+{code}/and_#{hashtag}

				/hashtags 
					/
					/search_{word}
					/for_U+{code}

				/mood
					/
					/for_U+{code}

		</pre>
	</main>
	
</body>
</html>