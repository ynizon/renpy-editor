<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo env("APP_NAME");?></title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
               /* display: flex;*/
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

			.links2{
				border-bottom:1px solid #ccc;								
				border-top:1px solid #ccc;								
			}
            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
			
			.screenshots{text-align:left;padding-top:40px;clear:both;margin: auto;width: fit-content;}
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    <br/><br/><?php echo env("APP_NAME");?>
                </div>

				<p style="color:#ee2222">
					<b>You want to create a visual novel with Ren'Py but you don't want to code.
					This tool is for you !</b>
				</p>
				
                <div class="links links2">
					<br/>
                    <a href="/screenshots">Screenshots</a>
                    <a href="https://www.renpy.org/">Ren'py</a>
                    <a href="https://games.renpy.org/">Ren'Py Games List</a>
                    <a href="http://fr.renpy.org/">Ren'py French</a>     
					<a href="https://www.renpy.org/doc/html/">Ren'py Doc</a>
					<br/>					<br/>					
                </div>
				<br/>					<br/>
                    
                    <script src="https://fast.wistia.com/embed/medias/5g9578thfk.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_5g9578thfk videoFoam=true" style="height:100%;position:relative;width:100%"><div class="wistia_swatch" style="height:100%;left:0;opacity:0;overflow:hidden;position:absolute;top:0;transition:opacity 200ms;width:100%;"><img src="https://fast.wistia.com/embed/medias/5g9578thfk/swatch" style="filter:blur(5px);height:100%;object-fit:contain;width:100%;" alt="" aria-hidden="true" onload="this.parentNode.style.opacity=1;" /></div></div></div></div>
                    
                    <br/>					<br/>					
				<footer >
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header" style="text-align:center">
										<b><a target="_blank" href='https://www.gameandme.fr/renpy-editor'>(c) Yohann Nizon 2019</a> - <a target="_blank" href='https://github.com/ynizon/renpy-editor'>Github Ren'py editor</a></b>
									</div>
								</div>
							</div>
						</div>			
					</div>
				</footer>
            </div>
        </div>
    </body>
</html>
