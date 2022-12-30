<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<style type="text/css">
		

body {
  margin: 0;
  font-family: "Nunito", sans-serif;
  font-size: 0.9rem;
  font-weight: 400;
  line-height: 1.6;
  color: #212529;
  text-align: left;
  background-color: #f8fafc;
}

@page {
                 margin: 0cm 0cm;
            }

            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 0.2cm;
                width: 50%;

                /** Extra personal styles **/
                background-color: #5c5449;
                color: white;
                text-align: center;
                line-height: 0.2cm;
            }

            footer {
                position: fixed;
                bottom: 0cm;
                left: 0cm;
                right: 0cm;
               	height: 0.2cm;
                width: 50%;

                /** Extra personal styles **/
                background-color: #5c5449;
                color: white;
                text-align: center;
                line-height: 0.2cm;
            }

            body {
            	font-family: DejaVu Sans, sans-serif, 'BPG WEB 001 Caps';
                margin-top: 0.2cm;
                
                
                margin-bottom: 0.2cm;
            }

            table {
            	border-collapse: collapse;
            }

            /*table td {
            	border: 1px solid #000;
            }

            table tr {
            	padding: 0.1cm 0;
            }*/


            .left {
            	padding-top: 0.1cm;
            	padding-left: 0.1cm;
            	padding-bottom: 0.2cm;
            }

            .right {
            	padding-top: 0.1cm;
            	padding-right: 0.1cm;
            	padding-bottom: 0.2cm;
            }
	</style>
</head>
<body>

	<script type="text/php">
      if ( isset($pdf) ) { 
          $pdf->page_script(' 
          if ($PAGE_COUNT > 1) {
            $text = __("გვერდი :pageNum/:pageCount", ["pageNum" => $PAGE_NUM, "pageCount" => $PAGE_COUNT]);
            $font = null;
            $size = 9;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default

            // Compute text width to center correctly
            $textWidth = $fontMetrics->getTextWidth($text, $font, $size);

            $x = ($pdf->get_width() - $textWidth) / 2;
            $y = $pdf->get_height() - 20;

            $pdf->text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            }
        '); // End of page_script
      }
      </script>

	{{-- @php
		$let = $model->items[0]['nested'][1]['media'][0];
		$let2 = $model->items[0]['nested'][0]['media'][0];

		// print_r(public_path('media/34/'.$let['file_name']));
	@endphp --}}

	<header>
            
        </header>

        <footer>
            
        </footer>

        <main>

	<div style="padding:0.5cm;">
		
		<center><h4 style="padding:0; margin: 0;" class="h4 text-center">აქტი: {{$model['uuid']}}</h4></center>

		<div class="d-flex flex-column-reverse">
			<div class="row">
			<div class="col"><b>შემსრულებელი:</b></div>
		</div>
		<div class="row">
			<div class="col" style="color: #5c5449;">შპს „ინსერვისი“</div>
		</div>
		<div class="row">
			<div class="col" style="color: #5c5449;">ტექნიკური განყოფილება</div>
		</div>
		</div>

		<div class="d-flex flex-column-reverse align-items-end" style="float: right;">
			<div class="row">
			<div class="col"><b>კვლევის ობიექტი :</b></div>
		</div>
		<div class="row">
			<div class="col" style="color: #5c5449;">{{$model['name']}}</div>
		</div>
		<div class="row">
			<div class="col" style="color: #5c5449;">{{$model['subject_name']}}</div>
		</div>
		<div class="row">
			<div class="col" style="color: #5c5449;">{{$model['identification_num']}}</div>
		</div>
		<div class="row">
			<div class="col" style="color: #5c5449;">{{$model['subject_address']}}</div>
		</div>
		<div class="row">
			<div class="col" style="color: #5c5449;"><b>თარიღი : {{date_format(date_create($model['created_at']), 'Y-m-d')}}</b></div>
		</div>
		</div>

		<div style="clear: both;"></div>

		@foreach ($model['items'] as $key => $item)
        
        {{-- @if(count($model['items']) > 1) <center><span>N: {{$key + 1}} </span></center> @endif --}}
        
		{{-- <hr style="width:50%; color: #5c5449;" /> --}}

		@foreach ($item['nested'] as $nestedKey => $nestedItem)

		

		

		{{-- @php 


		print_r(count($item->nested)) @endphp --}}

<div>

			<h4 class="h4" style="padding:0; margin: 0;">{{$nestedItem['title']}}</h4>

			

				
					<div style="color: #5c5449; padding-top: 0.2cm;">{{$nestedItem['value']}}</div>

					@if(isset($nestedItem['media']) && !empty($nestedItem['media']) && count($nestedItem['media']))
					<div style="margin-top: 0.3cm;">
						<table  style="width: 100%;">
							<thead>

								@foreach ($nestedItem['media']->chunk(2) as $chunk)

								<tr style="margin-top: 0.1cm; border: 2px solod #000000;">

									@foreach ($chunk as $chunkIndex => $product)

           <td width="width: 50" valign="center" class="{{($chunkIndex % 2 == 0 ? 'right' : 'left')}}" style="width: 50%; vertical-align: middle;">
									<img style="max-width: 100%;"  src="{{$product->original_url}}">
								</td>

								@if(count($chunk) == 1) <td></td> @endif
        @endforeach
s
									</tr>
								    
								@endforeach

								
								{{-- <td valign="center" style="width: 50%; padding:0px; vertical-align: middle;">
									<img style="max-width: 100%; max-height: 470px; height: 100%;"  src="https://demosdemo7.ptly.eu/demos/demo7/bulletin/177_1618823275/Test.png">
								</td>
								<td width="50%" valign="center" align="center" style="width: 50%; vertical-align: middle;">
									<img style="max-width: 100%; max-height: 470px; height: 100%;"; src="https://demosdemo7.ptly.eu/demos/demo7/bulletin/177_1618823275/Test.png">
								</td> --}}
							

							
							</thead>
							
							
						</table>
					</div>
					@endif
			
		
	



</div>
<hr/>
<br/>

@endforeach

		@endforeach

		

{{-- <div>

			<h4 class="h4" style="padding:0; margin: 0;">დაზიანების პირველადი აღწერილობა:</h4>

			

				
					<div>უკუსარქველი არასწორი მიმართულებით ატარებდა წყალს.</div>

					
			
		
	



</div>
<hr/>
<br/> --}}

{{-- <div>

			<h4 class="h4" style="padding:0; margin: 0;">დასკვნა:</h4>

			

				
					<div>დაფიქსირდა, რომ უკუსარქველი არასწორი მიმართულებით ატარებს წყალს, ხოლო
მასთან დამონტაჟებული ბურთულიანი ვენტილი არ ახდენს დაკეტვას. საჭიროა შეცვლა.</div>

					
			
		
	



</div>

<hr/>
<br/> --}}


<br/>

<div>

			<h4 class="h4" style="padding:0; margin: 0;">დეფექტური აქტის ავტორი:</h4>

			

				
					<div style="color: #5c5449;">შპს „ინსერვისი“</div>

					
			
		
	
<div>
	<table style="width:100%; padding: 0; color: #5c5449;">
		<thead>
		<tr>
			<td valign="center" style="width:33.33%  padding: 0; vertical-align: middle;">ტექნიკური მენეჯერი</td>
			<td valign="center" style="width:33.33%; padding: 0; vertical-align: middle; border-bottom: 1px solid #000; text-align: center;">
				<img style="max-width: 55%;" src="{{$model->user->getFirstMediaUrl('credential')}}">
			</td>
			<td valign="center" style="width:33.33%; padding: 0; vertical-align: middle; text-align: right;">{{$model->user->name}}</td>
		</tr>
		</thead>
	</table>
</div>


</div>

</div>

</body>
</html>