@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
			<div class="card-header"><a href='/home'><?php echo $story->name;?></a>&nbsp;&nbsp;>&nbsp;&nbsp;Decision Tree (reload for new colors)</div>

               <div class="card-body">
                    <div class="col-md-12">							
                         @include('story/part_line', ['story' => $story])
                        <hr/>
                    </div>
                    
                    
                    <div class="tree" id="tree"></div>                    
                    <script type="application/javascript">
                         $(document).ready(function() {
                              Highcharts.chart('tree', {
                                  chart: {
                                      height: <?php echo $iMaxLevel*100;?>,
                                      inverted: true
                                  },
                                   credits: {
                                          enabled: false
                                   },	
                                  title: {
                                      text: <?php echo json_encode($story->name);?>
                                  },

                                  series: [{
                                      type: 'organization',
                                      name: 'Story',
                                      keys: ['from', 'to'],
                                      data: 
                                          <?php echo json_encode($from_to);?>
                                      ,
                                      nodes: 
                                        <?php echo json_encode($scenes_data);?>
                                      ,
                                      colorByPoint: false,
                                      color: '#007ad0',
                                      dataLabels: {
                                          color: 'white'
                                      },
                                      borderColor: 'white',
                                      nodeWidth: 65
                                  }],
                                  tooltip: {
                                      outside: true,
                                      enabled: false 
                                  },
                                  exporting: {
                                      allowHTML: true,
                                      sourceWidth: 800,
                                      sourceHeight: 600
                                  }

                              });
                         });
                    </script>                    		
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
