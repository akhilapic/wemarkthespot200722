<?php

 $base_url =  URL::to('/');
?>
<style>
   .replys {
   position: relative;
   color: #d66821;
   }
   label.error {
   display: inline-block;
   width: 100%;
   clear: both;
   margin-top: 8px;
   color: #db0707;
   }
   
   .commutyReview .user-Detail figure {
    float: none;
    border-radius: 4px;
}
.commutyReview .comment_img {
    width: 90px;
    height: 90px;
    /*float: left;*/
    display: inline-block;
    margin-right: 10px;
    border-radius: 8px !important;
    border: 1px solid #bdbdbd;
    float: none !important;
}
.commutyReview .comment_video {
    width: 150px;
    height: 150px;
    display: inline-block;
    margin-right: 10px;
    border-radius: 8px !important;
    border: 1px solid #bdbdbd;
}
.BoxShade.commutyReview {
    margin-bottom: 15px;
}
</style>
@include("inc/header");
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<main class="community-review">
         <div class="container-fluid">
            <h1 class="title">Hotspot Updates</h1>
            <script>
             $(function(){

                  function GetMonthName(monthNumber) {
                     var months = ['Jan', 'Feb', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Dec'];
                     return months[monthNumber - 1];
                     }

                  var app = @json($hotspot);
               //   console.log(app.length);
                  // for(i=0;i<app[0].length;i++)
                  // {
                  //     html = '<div class="BoxShade commutyReview">';
                  //    html +='<figure> ';
                  //    if(app[0][i].user.image!='')
                  //    {

                  //       html +='<figure><img src="'+app[0][i].user.image+'"></figure>';
                  
                  //    }
                  //    else{
                  //          html += '<img src="{{asset('assets/images/img-3.png')}}">';
                  //    }
                  
                  //    html += '</figure>';         
                  //    html += '<div class="user-Detail">';         
                  //    html += '<h5>'+app[0][i].user.name+'</h5>';  
                  //    dd  = app[0][i].created_at.split('T');
                  
                  //    dateStr = dd[0].split('-');
                  //    dateYY = dateStr[0];
                  //    dateMM = dateStr[1];
                  //    dateDD = dateStr[2];
                     

                  //    html +=            '<p class="r-date">  '+dateDD +" " + GetMonthName(dateMM) +" " + dateYY+'</p>';

                  //    html +=             '<p>'+app[0][i].message+'</p>';
                  //    html +=            '<p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshow'+i+'" role="button" aria-expanded="false" aria-controls="reviewshow'+i+'">&nbsp;</p>'; 
                  //    html +=             '<div class="collapse" id="reviewshow'+i+'">';
                     

                  //    // chid start loop
                  //    html +=                '<div class="Allreply">';
                  //    if(app[0][i].children.length>0)
                  //    {
                  //       for(c=0;c<app[0][i].children.length;c++)
                  //       {
                  //          if(app[0][i].children[c].user.image)
                  //          {
                  //             html +='                <figure><img src="'+app[0][i].children[c].user.image+'"></figure>';
                  //          }
                  //          else
                  //          {
                  //             html +='                <figure><img src="{{asset('assets/images/img-3.png')}}"></figure>';
                  //          }
                  //          html +=                   '<div class="review-detail">';
                  //          html +=                      '<h6>'+app[0][i].children[c].user.name+'</h6>';
                  //          html +=                      '<p>'+app[0][i].children[c].message+'</p>';
                  //          html +=                   '</div>';
                  //          html +=                '</div>';
                  //       }
                           
                  //    }
                     
                  //    //chid end loop 
                  //    html +=             '</div>';
                  //    html +=             '<!-- <a href="#" class="reply">Reply</a> -->';
                  //    html +=          '</div>';
                  //    html +=       '</div>';
                  //    $("#communityReviewData1").append(html);
                  // }

                  //----------------------------------------------------Updated Code start--------------------------------------------------
                  for(i=0;i<app.length;i++)
                  {
                  //  console.log(app[i]);
                     topfirst = app[i];
                     
                     firstlevel = app[i].replies;
                  //   console.log(firstlevel);
                     html='<div class="BoxShade commutyReview">';
               //    html+='       <figure><img src="{{asset('assets/images/img-3.png')}}"></figure>';
                     
                     if(topfirst.user_image!=null)
                     {
                        html+='<figure><img src="'+topfirst.user_image+'"></figure>';
                     }
                     else
                     {
                        html+='   <figure><img src="{{asset('assets/images/img-3.png')}}"></figure>';
                     }
                     html+='<div class="user-Detail">';
                     html += '<h5>'+topfirst.user_name+'</h5>'; 
                     dd  = topfirst.created_at.split('T');
                     dateStr = dd[0].split('-');
                     dateYY = dateStr[0];
                     dateMM = dateStr[1];
                     dateDD = dateStr[2];
                     html +=            '<p class="r-date">  '+dateDD +" " + GetMonthName(dateMM) +" " + dateYY+'</p>';
                     html+='  <p>'+topfirst.message+'</p>';

                     //frist child
                        if(firstlevel.length>0)
                        {
                           for(c=0;c<firstlevel.length;c++)
                           {
                              level2 = firstlevel[c].children;
                           
                              html+=' <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshow1" role="button" aria-expanded="false" aria-controls="reviewshow1">&nbsp;</p>';
                              html+=' <div class="collapse" id="reviewshow1">';
                              html+='     <div class="Allreply">';
                                 if(firstlevel[c].user.image!=null)
                                 {
                                    html+='<figure><img src="'+firstlevel[c].user.image+'"></figure>';
                                 }
                                 else
                                 {
                                    html+='<figure><img src="{{asset('assets/images/img-2.png')}}"></figure>';
                                 }
                              html+='          <div class="review-detail">';
                              html+='             <h6>'+firstlevel[c].user.name+'</h6>';
                              html+='           <p>'+firstlevel[c].message+'</p>';
                                 
                              if(firstlevel[c].video_image_status==1)
                                 {
                                    if(firstlevel[c].image!=null)
                                    {
                                    
                                       iamgeArr = firstlevel[c].image.split(",");
                                       if(iamgeArr.length>0)
                                       {
                                                chekcimgVideo = 0;
                                                for(img =0; img<iamgeArr.length;img++)
                                                {
                                                   var extension = iamgeArr[img].substr( (iamgeArr[img].lastIndexOf('.') +1) ).toLowerCase();
                                                   if(extension=="png" || extension=="jpg" || extension=="jpeg" || extension=="gif" || extension=="svg")
                                                   {
                                                      html+='<figure class="comment_img"><img src="'+iamgeArr[img]+'"></figure>';
                                                   }
                                             }
                                       }
                                       else
                                       {
                                          html+='<figure class="comment_img"><img src="{{asset('assets/images/img-3.png')}}"></figure>';
                                       }
                                    }
                                 }
                                 else if(firstlevel[c].video_image_status==2)
                                 {
                                    videos = firstlevel[c].image;
                                    str1 = videos.replace(/,\s*$/, "");  
                                       html+='<video controls  autoplay muted loop  class="comment_video"><source src="'+str1+'" ></video>';
                                 }
                              html+='      </div>';
                           
                              //level2
                                 if(level2.length > 0)
                                 {
                                    for(l2=0;l2<level2.length;l2++)
                                    {
                                       level3 = level2[l2].children;
                                 //      console.log(level3);
                                       html+=' <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshowl2" role="button" aria-expanded="false" aria-controls="reviewshowl2">&nbsp;</p>';
                                       html+='<div class="collapse" id="reviewshowl2">';
                                          html+='  <div class="Allreply">';
                                             html+='   <figure><img src="{{asset('assets/images/img-3.png')}}"></figure>';
                                          html+='  <div class="review-detail">';
                                                html+='   <h6>'+level2[l2].user.name+'</h6>';
                                                html+='  <p>'+level2[l2].message+'</p>';
                                          html+='  </div>';

                                          
                                       //level3 start
                                       if(level3.length>0)
                                       {
                                          for(l3=0;l3<level3.length;l3++)
                                          {
                                             html+=' <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshow3" role="button" aria-expanded="false" aria-controls="reviewshow3">&nbsp;</p>';
                                             html+='<div class="collapse" id="reviewshow3">';
                                             html+='<div class="Allreply">';
                                             if(level3[l3].user.image!=null)
                                             {
                                                html+='<figure><img src="'+level3[l3].user.image+'"></figure>';
                                             }
                                             else
                                             {
                                                html+='<figure><img src="{{asset('assets/images/img-2.png')}}"></figure>';
                                             }
                                             html+='<div class="review-detail">';
                                             html+='<h6>'+level3[l3].user.name+'</h6>';
                                             html+='<p>'+level3[l3].message+'</p>';
                                             html+='</div>';
                                             html+='</div>';
                                             html+='</div>';
                                          }
                                          
                                       }
                                       
                                       //level 3 end
                                          html+='   </div>';
                                    }
                                 }
                              
                                 //close level2

                                 html+='</div>';
                                 //close
                              html+='    </div>';
                           }
                        }
                     
               
                     html+='  </div>';
                     html+='   <!-- <a href="#" class="reply">Reply</a> -->';
                     html+='  </div>';
                     html+='  </div>';
                     $("#communityReviewData").append(html);
                  }
               
               })
               //  </script>
        
         </div>
         <div class="container-fluid"> 
         <div class="col-md-12" id="communityReviewData1">
            @foreach ($hotspot as $i=> $user)
            <div class="BoxShade commutyReview">
               <figure>
                     @if(!empty($user->user_image))
                        <img class="imgsrc" src="{{$user->user_image}}">
                        @else
                        <img class="imgsrc" src="{{asset('/images/userimage.png')}}">                     
                     @endif   
               </figure>
               <div class="user-Detail">

                  @if(!empty($user->user_name))
                     <h5>{{$user->user_name}}</h5>
                  @endif
                  <p class="r-date"> {{date('d M Y', strtotime($user->created_at))}}</p>
                  <p>{{$user->message}}</p>
                  <?php
                       if($user->video_image_status==1)
                       {
                           $imageArray = explode(",",$user->image);
                           if(count($imageArray)>0)
                           {
                              for($img =0; $img<count($imageArray);$img++)
                              {
                                 $path_parts = pathinfo($imageArray[$img]);
                                 if(!empty($path_parts['extension']))
                                 {
                                    $extension =$path_parts['extension']; 
                                    if($extension=="png" || $extension=="jpg" || $extension=="jpeg" || $extension=="gif" || $extension=="svg")
                                    {
                                       if(!empty($imageArray[$img]))
                                       {
                                          ?>
                                          <figure class="comment_img"><img alt="1" src="{{$imageArray[$img]}}"></figure>  
                                       <?php
                                       }
                                    }
                                 }
                              }
                           }
                           else
                           {
                              ?>
                              <figure class="comment_img"><img src="{{asset('/images/userimage.png')}}"></figure>
                              <?php
                           }
                       }
                       else if($user->video_image_status==2)
                       {
                            $edited=  rtrim($user->image, ",");
                        ?>
                           <video controls  autoplay muted loop  class="comment_video"><source src="<?php echo $edited;?>" ></video>
                        <?php
                       }
                       ?> 
                  <div class="" style="display: inline-block;width: 100%;">
                  <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshow{{$i}}" role="button" aria-expanded="false" aria-controls="reviewshow{{$i}}" style="display: inline-block;">&nbsp; </p>
                  </div>
                     <div class="collapse" id="reviewshow{{$i}}">
                           <!--reply start-->
                     <?php
                        $repliesArray = isset($user->replies)? $user->replies  :'';
                        if(!empty($repliesArray))
                        {
                           $count = 1;
                           for($r=0;$r<count($repliesArray);$r++)
                           {  
                              $level2 = isset($repliesArray[$r]['children']) ? $repliesArray[$r]['children']  :'';
                              ?>
                              <div class="Allreply">  
                                    <?php
                                       if($repliesArray[$r]['user']['image'])
                                       {
                                          ?>
                                          <figure><img src="{{$repliesArray[$r]['user']['image']}}"></figure>
                                          <?php
                                       }
                                       ?>
                                    <div class="review-detail">
                                    <h6>{{$repliesArray[$r]['user']['name']}}</h6>
                                       <p>{{$repliesArray[$r]['message']}}</p>
                                       <?php
                                          if($repliesArray[$r]['video_image_status']==1)
                                          {
                                                $imageArray = explode(",",$repliesArray[$r]['image']);
                                                if(count($imageArray)>0)
                                                {
                                                   for($img =0; $img<count($imageArray);$img++)
                                                   {
                                                      $path_parts = pathinfo($imageArray[$img]);
                                                      if(!empty($path_parts['extension']))
                                                      {
                                                         $extension =$path_parts['extension']; 
                                                         if($extension=="png" || $extension=="jpg" || $extension=="jpeg" || $extension=="gif" || $extension=="svg")
                                                         {
                                                          //  if(file_exists($imageArray[$img]))
                                                            //{
                                                               ?>
                                                               <figure class="comment_img"><img alt="1" src="{{$imageArray[$img]}}"></figure>  
                                                            <?php
                                                            //}
                                                         }
                                                      }
                                                   }
                                                }
                                                else
                                                {
                                                   ?>
                                                   <figure class="comment_img"><img src="{{asset('/images/userimage.png')}}"></figure>
                                                   <?php
                                                }
                                          }
                                          else if($repliesArray[$r]['video_image_status']==2)
                                          {
                                                $edited=  rtrim($repliesArray[$r]['image'], ",");
                                             ?>
                                                <video controls  autoplay muted loop  class="comment_video"><source src="<?php echo $edited;?>" ></video>
                                             <?php
                                          }
                                          ?>
                                         
                                    </div>
                                    <!--level2 start-->
                                    <?php
                                    if(count($level2)>0)
                                    {
                                       for($l2 =0 ; $l2<count($level2);$l2++)
                                       {
                                          $level3 = isset($level2[$l2]['children'])? $level2[$l2]['children'] : '';
                                          ?>
                                          <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshowl2{{$l2}}" role="button" aria-expanded="false" aria-controls="reviewshowl2{{$l2}}">&nbsp;</p>
                                          <div class="collapse" id="reviewshowl2{{$l2}}">
                                             <div class="Allreply">
                                             <?php
                                                if(isset($level2[$l2]['user']['image']))
                                                {
                                                   ?>
                                                   <figure><img src="{{$level2[$l2]['user']['image']}}"></figure>
                                                   <?php
                                                }
                                                ?>
                                                <div class="review-detail">
                                                 
                                                @if(!empty($level2[$l2]['user']['name']))
                                                <h6>{{$level2[$l2]['user']['name']}}</h6>
                                                @endif
                                                @if(!empty($level2[$l2]['message']))
                                                <p>{{$level2[$l2]['message']}}</p>
                                                @endif
                                                
                                                 <?php
                                                
                                          if($level2[$l2]['video_image_status']==1)
                                          {
                                                $imageArray = explode(",",$level2[$l2]['image']);
                                                if(count($imageArray)>0)
                                                {
                                                   for($img =0; $img<count($imageArray);$img++)
                                                   {
                                                      $path_parts = pathinfo($imageArray[$img]);
                                                      if(!empty($path_parts['extension']))
                                                      {
                                                         $extension =$path_parts['extension']; 
                                                         if($extension=="png" || $extension=="jpg" || $extension=="jpeg" || $extension=="gif" || $extension=="svg")
                                                         {
                                                          //  if(file_exists($imageArray[$img]))
                                                            //{
                                                               ?>
                                                               <figure class="comment_img"><img alt="1" src="{{$imageArray[$img]}}"></figure>  
                                                            <?php
                                                            //}
                                                         }
                                                      }
                                                   }
                                                }
                                                else
                                                {
                                                   ?>
                                                   <figure class="comment_img"><img src="{{asset('/images/userimage.png')}}"></figure>
                                                   <?php
                                                }
                                          }
                                          else if($level2[$l2]['video_image_status']==2)
                                          {
                                                $edited=  rtrim($level2[$l2]['image'], ",");
                                             ?>
                                                <video controls  autoplay muted loop  class="comment_video"><source src="<?php echo $edited;?>" ></video>
                                             <?php
                                          }
                                          ?>
                                          </div>
                                                <!---level 3 start-->
                                                <?php
                                                if(!empty($level3))
                                                {
                                                   if(count($level3)>0)
                                                   {
                                                      for($l3=0;$l3<count($level3);$l3++)
                                                      {
                                                         ?>
                                                         <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshow3{{$l3}}" role="button" aria-expanded="false" aria-controls="reviewshow3{{$l3}}">&nbsp;</p>
                                                         <div class="collapse" id="reviewshow3{{$l3}}">
                                                            <div class="Allreply">
                                                               <?php
                                                                  if(!empty($level3[$l3]['user']['image']))
                                                                  {
                                                                     ?>
                                                                     <figure><img src="{{$level3[$l3]['user']['image']}}"></figure>
                                                                     <?php
                                                                  }
                                                                  else
                                                                  {
                                                                     ?>
                                                                     <img class="imgsrc" src="{{asset('/images/userimage.png')}}">  
                                                                     <?php
                                                                  }
                                                               ?>
                                                               <div class="review-detail">
                                                               <h6>{{$level3[$l3]['user']['name']}}</h6>
                                                               <p>{{$level3[$l3]['message']}}</p>
                                                               <?php
                                                                  if($level3[$l3]['video_image_status']==1)
                                                                  {
                                                                        $imageArray3 = explode(",",$level3[$l3]->image);
                                                                        if(count($imageArray3)>0)
                                                                        {
                                                                           for($img3 =0; $img3<count($imageArray3);$img3++)
                                                                           {
                                                                              $path_parts3 = pathinfo($imageArray3[$img3]);
                                                                              if(!empty($path_parts3['extension']))
                                                                              {
                                                                                 $extension3 =$path_parts3['extension']; 
                                                                                 if($extension3=="png" || $extension3=="jpg" || $extension3=="jpeg" || $extension3=="gif" || $extension3=="svg")
                                                                                 {
                                                                                 //  if(file_exists($imageArray[$img]))
                                                                                    //{
                                                                                       ?>
                                                                                       <figure class="comment_img"><img alt="1" src="{{$imageArray3[$img3]}}"></figure>  
                                                                                    <?php
                                                                                    //}
                                                                                 }
                                                                              }
                                                                           }
                                                                        }
                                                                        else
                                                                        {
                                                                           ?>
                                                                           <figure class="comment_img"><img src="{{asset('/images/userimage.png')}}"></figure>
                                                                           <?php
                                                                        }
                                                                  }
                                                                  else if($repliesArray[$r]['video_image_status']==2)
                                                                  {
                                                                        $edited=  rtrim($repliesArray[$r]['image'], ",");
                                                                     ?>
                                                                        <video controls  autoplay muted loop  class="comment_video"><source src="<?php echo $edited;?>" ></video>
                                                                     <?php
                                                                  }
                                                                  ?>

                                                               </div>
                                                            </div>
                                                         </div>
                                                         <?php
                                                      }
                                                   }
                                                }
                                                ?>
                                                <!---level 3 end-->
                                             </div>
                                          </div>
                                          <?php
                                       }
                                    }
                                    ?>
                                    <!--level2 end-->
                              </div>
                              <?php
                           }
                           $count ++;
                        }
                        ?>
                        <!--reply end-->
                     </div>
                  </div>
               
            </div>
            @endforeach
            {{ $hotspot->links() }}
          </div>
          <div class="row currentCheck mt-4">
               <div class="col-md-6">
                  <h2>Current Check Ins {{$total_checkin_count}}</h2>
               </div>
               <div class="col-md-6 text-md-end">
                   <h2 class="color2">{{$totalCheckIns}} Check-ins</h2>
                </div>
            </div>
         </div>

         <div class="col-md-12" id="communityReviewData" style="display:none">
                       @foreach ($hotspot as $i=> $user)
                       <div class="BoxShade commutyReview">
                       <figure>
                           @if(!empty($user->user_image))
                              <img class="imgsrc" src="{{$user->user_image}}">
                              @else
                              <img class="imgsrc" src="{{asset('/images/userimage.png')}}">                     
                           @endif   
                     </figure>                          
                          <div class="user-Detail">
                          @if(!empty($user->user_name))
                              <h5>{{$user->user_name}}</h5>
                           @endif
                           <p class="r-date"> {{date('d M Y', strtotime($user->created_at))}}</p>
                               <p>{{$user->message}}</p><br>
                               <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshow1{{$i}}" role="button" aria-expanded="false" aria-controls="reviewshow1{{$i}}">&nbsp;</p> 
                                 <div class="collapse" id="reviewshow1{{$i}}">     
                                    <?php
                                          $repliesArray = isset($user->replies)? $user->replies  :'';
                                       if(!empty($repliesArray) && isset($repliesArray))
                                       {
                                          $count = 1;
                                          for($r=0;$r<count($repliesArray);$r++)
                                          {
                                             $level2 = isset($repliesArray[$r]['children']) ? $repliesArray[$r]['children']  :'';  
                                             ?>
                                                <div class="Allreply">  
                                                <?php
                                                      if($repliesArray[$r]['user']['image'])
                                                      {
                                                         ?>
                                                         <figure><img src="{{$repliesArray[$r]['user']['image']}}"></figure>
                                                         <?php
                                                      }
                                                      ?>   
                                                   <div class="review-detail">
                                                      <h6>{{$repliesArray[$r]['user']['name']}}</h6>
                                                      <p>{{$repliesArray[$r]['message']}}</p>
                                                      <?php
                                                      if($repliesArray[$r]['video_image_status']==1)
                                                         {
                                                            $imageArray = explode(",",$user->image);
                                                            if(count($imageArray)>0)
                                                            {
                                                               for($img =0; $img<count($imageArray);$img++)
                                                               {
                                                                  $path_parts = pathinfo($imageArray[$img]);
                                                                  if(!empty($path_parts['extension']))
                                                                  {
                                                                    $extension =$path_parts['extension']; 
                                                                    if($extension=="png" || $extension=="jpg" || $extension=="jpeg" || $extension=="gif" || $extension=="svg")
                                                                     {
                                                                          ?>
                                                                           <figure class="comment_img"><img alt="1" src="{{$imageArray[$img]}}"></figure>  
                                                                        <?php
                                                                     }
                                                                  }
                                                               }
                                                            }
                                                            else
                                                            {
                                                               ?>
                                                               <figure class="comment_img"><img src="{{asset('/images/userimage.png')}}"></figure>
                                                               <?php
                                                            }
                                                         }
                                                      ?>
                                                   </div>
                                                   <br/>
                                                   <!--level2 start-->
                                                   <?php
                                                          if(count($level2)>0) 
                                                          {
                                                            for($l2 =0 ; $l2<count($level2);$l2++)
                                                            {
                                                               $level3 = isset($level2[$l2]['children'])? $level2[$l2]['children'] : '';
                                                            }
                                                          }                                           
                                                   ?>
                                                   <!--level2 end-->

                                                </div>
                                             <?php
                                          }
                                       }
                                    ?>
                                       <div class="Allreply">
                                          <figure><img src="https://builtenance.com/development/wemarkthespot/public/images/2440852b0c0d452c789f173c5743009e.jpg"></figure>          
                                          <div class="review-detail">             
                                             <h6>Testingperson</h6>           
                                             <p>nfcncjfff</p>      
                                          </div> 
                                          <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshowl2" role="button" aria-expanded="false" aria-controls="reviewshowl2">&nbsp;</p>
                                             <div class="collapse" id="reviewshowl2">  
                                                <div class="Allreply">   
                                                   <figure><img src="https://builtenance.com/development/wemarkthespot/assets/images/img-3.png"></figure>  
                                                   <div class="review-detail">   
                                                      <h6>Testingperson</h6>  
                                                      <p>gshssdu</p>  
                                                   </div> 
                                                   <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshow3" role="button" aria-expanded="false" aria-controls="reviewshow3">&nbsp;</p>
                                                      <div class="collapse" id="reviewshow3">
                                                            <div class="Allreply">
                                                               <figure>
                                                                  <img src="https://builtenance.com/development/wemarkthespot/public/images/2440852b0c0d452c789f173c5743009e.jpg">
                                                               </figure>
                                                               <div class="review-detail">
                                                                  <h6>Testingperson</h6>
                                                                  <p>xjffdjd</p>
                                                               </div></div></div>   </div> <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshowl2" role="button" aria-expanded="false" aria-controls="reviewshowl2">&nbsp;</p><div class="collapse" id="reviewshowl2">  <div class="Allreply">   <figure><img src="https://builtenance.com/development/wemarkthespot/assets/images/img-3.png"></figure>  <div class="review-detail">   <h6>Testingperson</h6>  <p>hdhduududud</p>  </div>   </div></div>    </div> <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshow1" role="button" aria-expanded="false" aria-controls="reviewshow1">&nbsp;</p> <div class="collapse" id="reviewshow1">     <div class="Allreply"><figure><img src="https://builtenance.com/development/wemarkthespot/public/images/2440852b0c0d452c789f173c5743009e.jpg"></figure>          <div class="review-detail">             <h6>Testingperson</h6>           <p>hccj cc jcucff</p>      </div> <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshowl2" role="button" aria-expanded="false" aria-controls="reviewshowl2">&nbsp;</p><div class="collapse" id="reviewshowl2">  <div class="Allreply">   <figure><img src="https://builtenance.com/development/wemarkthespot/assets/images/img-3.png"></figure>  <div class="review-detail">   <h6>Testingperson</h6>  <p>jfufudd</p>  </div> <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshow3" role="button" aria-expanded="false" aria-controls="reviewshow3">&nbsp;</p><div class="collapse" id="reviewshow3"><div class="Allreply"><figure><img src="https://builtenance.com/development/wemarkthespot/public/images/88dccc7896ba71ae42c3fd1837232eda.jpg"></figure><div class="review-detail"><h6>rajat agrawal</h6><p>j</p></div></div></div>   </div> <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshowl2" role="button" aria-expanded="false" aria-controls="reviewshowl2">&nbsp;</p><div class="collapse" id="reviewshowl2">  <div class="Allreply">   <figure><img src="https://builtenance.com/development/wemarkthespot/assets/images/img-3.png"></figure>  <div class="review-detail">   <h6>rajat agrawal</h6>  <p>hu</p>  </div>   </div></div>    </div>  </div>   <!-- <a href="#" class="reply">Reply</a> -->  </div>  </div></div></div></div>
                        @endforeach

                         {{ $hotspot->links() }}
               </div>
          
         </div>
      </main>

@include("inc/footer");
<script type="text/javascript">
    $(document).ready(function(e) {
      $(".nav-item a").removeClass("active");
      $("#hotspot-updates").addClass('active');
    });
 </script>

 <style>
    .commutyReview figure img {
         border-radius: 50%;
   }
    svg{
         width: 20px;
      }
   .justify-between .hidden .text-gray-700 {
         margin-top: 16px;
   }
   .justify-between .flex.flex-1 {
         margin-top: 18px;
         display: none;
   }
   
   
   nav.flex.items-center.justify-between .hidden {
    display: flex;
    align-items: center;
    width: 100%;
	    margin-top: 20px;
}
nav.flex.items-center.justify-between .hidden > div {
    width: 50%;
}
nav.flex.items-center.justify-between .hidden > div:nth-child(2) {
    justify-content: right;
    text-align: right;

}

nav.flex.items-center.justify-between .hidden > div:nth-child(2) .relative {
    /*display: inline-block;*/
	 box-shadow: none !important;
    display: flex;
    justify-content: right;
}
nav.flex.items-center.justify-between .hidden > div:nth-child(2) .relative a {
    margin: 0 !important;
    width: 40px;
    padding: 0px !important;
    border-radius: 50%;
    height: 40px;
    line-height: 40px;
    text-align: center;
}
nav.flex.items-center.justify-between .hidden > div:nth-child(2) .relative span .relative {
    margin: 0 !important;
    width: 40px;
    padding: 0px !important;
    border-radius: 50%;
    height: 40px;
    line-height: 40px;
    text-align: center;
}
span.relative.inline-flex.items-center.px-2.py-2.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.cursor-default.rounded-l-md.leading-5 {
    display: block;
}
span.relative.inline-flex.items-center.px-2.py-2.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.cursor-default.rounded-l-md.leading-5 svg {
    padding-top: 9px;
}
.relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.cursor-default.leading-5 {
    display: block;
}
a.relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-gray-700.bg-white.border.border-gray-300.leading-5.hover\:text-gray-500.focus\:z-10.focus\:outline-none.focus\:ring.ring-gray-300.focus\:border-blue-300.active\:bg-gray-100.active\:text-gray-700.transition.ease-in-out.duration-150 {
    display: block;
}
a.relative.inline-flex.items-center.px-2.py-2.-ml-px.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.rounded-r-md.leading-5.hover\:text-gray-400.focus\:z-10.focus\:outline-none.focus\:ring.ring-gray-300.focus\:border-blue-300.active\:bg-gray-100.active\:text-gray-500.transition.ease-in-out.duration-150 {
    display: block;
}
a.relative.inline-flex.items-center.px-2.py-2.-ml-px.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.rounded-r-md.leading-5.hover\:text-gray-400.focus\:z-10.focus\:outline-none.focus\:ring.ring-gray-300.focus\:border-blue-300.active\:bg-gray-100.active\:text-gray-500.transition.ease-in-out.duration-150 svg {
    padding-top: 9px;
}


 </style>
