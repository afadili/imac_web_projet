<!DOCTYPE html>
<html>
<head>
	<title>Hello</title>
	<style type="text/css" media="screen">
		body
		{
			font-family: sans-serif;
			max-width: 40rem;
			margin:  auto;
			margin-bottom: 6rem;
		}

		code 
		{
			display: inline-block;
			background: #eee;
			padding: .1rem .5rem;
			border-radius: .2rem;
			border:  solid 1px #CCC;
		}

		h1,h2,h3,h4,h5,h6{
			margin-bottom: .5rem;
			margin-top: 2em;
		}

		h4{
			background: #FAFAFA;
			padding: .5rem 0;
			border-top:  solid 1px #ccc;
		}

		ul, p {
			margin: .5rem 0;
		}

		i{
			color:  red;
		}
	</style>
</head>
<body>
	<header id="header" class="">
		<h1>Emoji-tracker API</h1>	
		<p>Back-end api for <em>Emoji-tracker</em> a analytics tool for emoji usage on tweeter.</p>
		<p>Part of university project in web developpement: <a href="https://www.ingenieur-imac.fr">IMAC</a>/<a href="https://www.u-pem.fr">UPEM</a></p>
		<a href="http://server.piergabory.com:5000/">Emoji-tracker App</a>
		<hr>
	</header><!-- /header -->
	
	<main>
		<h2>Endpoints</h2>
		<nav>
			<ul>
				<li><a href='#Ranking'>Ranking</a></li>
				<li><a href='#History'>History</a></li>
				<li><a href='#Emoji'>Emoji</a></li>
				<li><a href='#Mood'>Mood</a></li>
				<li><a href='#Hashtag'>Hashtag</a></li>
				<li><a href='#Statistics'>Statistics</a></li>
			</ul>
		</nav>

		<article>
			<h4 id='Ranking'>Ranking</h4>
			
			<code>api/ranking/by_{<i>method</i>}/[since_{<i>date</i>} || between_{<i>date</i>}_and_{<i>date</i>} || until_{<i>date</i>}]</code>

			<ul>
				<li><i>Method</i>: String in <code>'usage','average_retweets','average_favorites','average_responses','average_popularity'</code></li>
				<li><i>date</i>: Int timestamp</li>
			</ul>

			<p>Return sorted list of emojis. The sorting method is defined by <code>{method}</code>. </p>
		</article>

		<article>
			<h4 id='History'>History</h4>

			<code>api/history/U+{<i>code</i>}/of_{<i>method</i>}/[since_{<i>date</i>} || between_{<i>date</i>}_and_{<i>date</i>} || until_{<i>date</i>}]</code>

			<ul>
				<li><i>Code</i>: Hexadecimal value, Unicode (ex: U+F103E) </li>
				<li><i>Method</i>: String in <code>'usage','average_retweets','average_favorites','average_responses','average_popularity'</code></li>
				<li><i>date</i>: Int timestamp</li>
			</ul>

			<p>Return array of number and dates corresponding to every datapoint of the given emoji over time.</p>
		</article>

		<article>
			<h4 id='Emoji'>Emoji</h4>
			<code>api/emoji/</code>
			<p>Returns all referenced emoji. Objects containing info such as Unicodes, ASCII representation ':-)', Name, description etc...</p>

			<code>api/emoji/characters</code>
			<p>Returns all referenced emoji as an array of characters.</p>	
			<code>api/emoji/U+{<i>code</i>}</code>
			<ul>
				<li><i>Code</i>: Hexadecimal value, Unicode (ex: U+F103E) </li>
			</ul>
			<p>Returns matching emoji</p>

			<code>api/emoji/search_{<i>needle</i>}</code>
			<ul>
				<li><i>needle</i>: String</li>
			</ul>
			<p>Returns list of emojis with names, code, character or anything matching the <i>needle</i></p>

			<code>api/emoji/by_mood_{<i>word</i>}</code><br>
			<code>api/emoji/by_hashtag_{<i>word</i>}</code>
			<ul>
				<li><i>word</i>: String</li>
			</ul>
			<p>Returns all emojis used with an hashtag or representing a mood. Word must be a referenced hashtag/mood.</p>
		</article>

		<article>
			<h4 id="Mood">Mood</h4>
			<code>api/mood/</code>
			<p>Returns all referenced moods.</p>

			<code>api/mood/search_{<i>needle</i>}</code>
			<ul>
				<li><i>needle</i>: String</li>
			</ul>
			<p>Returns list of mood with names matching the <i>needle</i></p>

			<code>api/mood/for_U+{<i>code</i>}</code>
			<ul>
				<li><i>code</i>: Hexadecimal value, Unicode</li>
			</ul>
			<p>Returns list of mood linked with the emoji</p>
		</article>

		<article>
			<h4 id="Hashtag">Hashtag</h4>
			<code>api/hashtag/</code>
			<p>Returns all referenced hashtags.</p>

			<code>api/hashtag/search_{<i>needle</i>}</code>
			<ul>
				<li><i>needle</i>: String</li>
			</ul>
			<p>Returns list of hashtag with names matching the <i>needle</i></p>

			<code>api/hashtag/for_U+{<i>code</i>}</code>
			<ul>
				<li><i>code</i>: Hexadecimal value, Unicode</li>
			</ul>
			<p>Returns list of hashtag used with the emoji</p>
		</article>


		<article>
			<h4 id="Statistics">Statistics</h4>

			<code>api/hashtag/for_U+{<i>code</i>}</code>
			<ul>
				<li><i>code</i>: Hexadecimal value, Unicode</li>
			</ul>
			<p>Returns list of statistics linked with the emoji</p>


			<code>api/hashtag/for_U+{<i>code</i>}/and_#{<i>tag</i>}</code>
			<ul>
				<li><i>code</i>: Hexadecimal value, Unicode</li>
				<li><i>tag</i>: String</li>
			</ul>
			<p>Returns list of statistics linked with the context of emoji used with hashtag</p>
		</article>

	</main>
</body>
</html>