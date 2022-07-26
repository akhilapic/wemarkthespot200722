<?php
   $base_url =  URL::to('/');
?>
   <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<?php echo $__env->make("inc/header", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
/*.stylenextdiv{
   margin-top: 110px;
}*/
</style>

<script>
   function likedislike(businessreview_id,business_id,likedislike)
   {
      $.ajax({
            url: "<?php echo e(route('likedislikeweb')); ?>",
            type:'POST',
            'async': false,
        'global': false,
        'dataType': "json",
            data: {_token:"<?php echo e(csrf_token()); ?>", businessreview_id:businessreview_id, business_id:business_id,likedislike:likedislike},
            success: function(data) {
            if(data.status==true)
               {
                  window.location.reload();
               }
         }
                });
   }
   $(function(){
      
      $(".clklikedislike").on("click",function(){
         console.log("sd");
      });
      
   
      function GetMonthName(monthNumber) {
         var months = ['Jan', 'Feb', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Dec'];
         return months[monthNumber - 1];
         }
   

      var app = <?php echo json_encode($BusinessReview1, 15, 512) ?>;
        
        console.log(app);
      
       if(app.length >0)
         {
            for(i=0;i<app.length;i++)
            {
                  html='<div class="col-md-12">';
                  html+='<div class="BoxShade commutyReview">';
                  fileexistsCheck(app[i].user_image,i);
                  if(app[i].user_image)
                  {
                    html+='<figure><img  id="imgsrc'+i+'" class="imgsrcClass" src="'+app[i].user_image+'" ></figure>';
                  }
                  else{
                     html+='<figure><img id="imgsrc'+i+'" class="imgsrcClass" src="<?php echo e(asset('assets/images/img-3.png')); ?>"></figure>';
                  }
                     html+='<div class="user-Detail">';
                        html+='<h5>'+app[i].user_name+'</h5>';
                        dd  = app[i].created_at.split('T');
                        dateStr = dd[0].split('-');
                     dateYY = dateStr[0];
                     dateMM = dateStr[1];
                     dateDD = dateStr[2];
                     url = "community_reportweb/"+app[i].id+"/"+app[i].id;
                     html +='<p class="r-date">  '+dateDD +" " + GetMonthName(dateMM) +" " + dateYY+'</p>';
                        html+='<ul class="share-up">';
                        if(app[i].report ==1)
                        {
                           html+='<li><a href="javascript:void(0)" style="cursor: no-drop">Reported</a></li>';
                        }
                        else
                        {
                           html+='<li><a href="'+url+'" data-business_id="'+app[i].id+'" data-review_id="'+app[i].id+'">Report</a></li>';
                        }
   
                           html +='<li class="clklikedislike" onclick="return likedislike('+app[i].id+','+app[i].business_id+','+1+')"  data-businessreview_id='+app[i].id+' data-business_id='+app[i].business_id+' data-like="1"><span class="icon-thumbs-up "  data-businessreview_id='+app[i].id+' data-business_id='+app[i].business_id+' data-llike="1"></span> '+app[i].total_like+'</li>';
   
                           html +='<li class="clklikedislike" onclick="return likedislike('+app[i].id+','+app[i].business_id+','+2+')"  data-businessreview_id='+app[i].id+' data-business_id='+app[i].business_id+' data-llike="2" class="" ><span class="icon-thumbs-down"></span> '+app[i].total_dislike+'</li>';
                        html+='</ul>';
                        html+='<p>'+app[i].review +'</p>';

                        if(app[i].image_video_status==1)
                        {
                            iamgeArr = app[i].image.split(",");
                    
                           if(iamgeArr.length>0)
                              {
                                 for(img =0; img<iamgeArr.length;img++)
                                 {
                                    var extension = iamgeArr[img].substr( (iamgeArr[img].lastIndexOf('.') +1) ).toLowerCase();
                                    console.log(extension);
                                    if(extension=="png" || extension=="jpg" || extension=="jpeg" || extension=="gif" || extension=="svg")
                                    {
                                       html+='<figure class="comment_img"><img src="'+iamgeArr[img]+'"></figure>';
                                    }
                                 }
                              }
                              else{
                                 html+='<figure class="comment_img"><img src="<?php echo e(asset('assets/images/img-3.png')); ?>"></figure>';
                              }
                        }
                        else if(app[i].image_video_status==2)
                        {
                           var edited = app[i].image.replace(/^,|,$/g,'');

                            html+='<video controls  autoplay muted loop  class="comment_video"><source src="'+edited+'" ></video>';
                        }
                        
   
                        html+='<div class="" style="display: inline-block;width: 100%;"><p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshow'+i+'" role="button" aria-expanded="false" aria-controls="reviewshow'+i+'" style="display: inline-block;">&nbsp; </p><a href="#" class="reply btnreply" data-review_id ="'+app[i].id+'" data-type="REVIEW" data-reply_id ="'+app[i].id+'">Reply</a></div>';
   
                        html+='<div class="collapse" id="reviewshow'+i+'">';
                           //reply start
                              if(app[i].replies.length>0)
                              {
                                 repliesData = app[i].replies;
                                 count =1;
                                 for(r=0;r<repliesData.length;r++)
                                 {
                                  //  console.log(repliesData[r]);
                                    html+='<div class="Allreply">';
                                    if(repliesData[r].user.image)
                                    {
                                       html+='<figure><img src="'+repliesData[r].user.image+'"></figure>';
                                    }
                                    else
                                    {
                                       //html+='<figure><img src="<?php echo e(asset('assets/images/img-3.png')); ?>"></figure>';
                                    }
                                    html+='<div class="review-detail">';
                                       html+='<h6>'+repliesData[r].user.name+'</h6>';
                                       html+='<p>'+repliesData[r].message+'</p>';
                                       html+='<p>'+repliesData[r].image+'</p>';
                                       if(repliesData.length==count)
                                       {
                                          html+='<p data-bs-toggle="collapse" style="display:inline-block;" class="replys " href="#reviewshow'+r+'" role="button" aria-expanded="false" aria-controls="reviewshow'+r+'"><img src="<?php echo e(asset('assets/images/reply.png')); ?>" width="15px"> '+repliesData[r].children.length+' Reply </p>';
                                          html+='<a href="#"  class="reply btnreply ms-2" data-review_id ="'+app[i].id+'" data-type="REVIEW" data-reply_id ="'+repliesData[r].id+'">Reply</a>';  
                            
                                       }
                                       else{
                                          html+='<p><div class="d-block"><a href="#"  class="reply btnreply ms-2" data-review_id ="'+app[i].id+'" data-type="REVIEW" data-reply_id ="'+repliesData[r].id+'">Reply</a></div></p>';
                                       }
                             
                                          //end level 1
                                          if(repliesData.length==count)
                                          {
                                             level1 = repliesData[r].children;
                                          //   console.log("level-1->"+level1);
                                             if(level1.length>0)
                                             {
                                                countlevel=1;
                                                for(l1 = 0; l1<level1.length;l1++)
                                                 {
                                                   level2 = level1[l1].children;
   
                                                   html+='<div class="collapse subthreads" id="reviewshow'+r+'">';
                                                   html+='<div class="Allreply">';
                                                      if(level1[l1].user.image)
                                                         {
                                                            html+='<figure><img src="'+level1[l1].user.image+'"></figure>';
                                                      
                                                         }else
                                                         {
                                                            html+='<figure><img src="<?php echo e(asset('assets/images/img-3.png')); ?>"></figure>';
                                                         }
                                                         html+='<div class="review-detail">';
                                                         html+='<h6>'+level1[l1].user.name+'</h6>';
                                                         html+='<p>'+level1[l1].message+'</p>';
                                                         html+='<div/>';
                                                      html+='<div/>';
                                                  

                                                      if(level1.length==countlevel)
                                                      {
                                                         html+='<p data-bs-toggle="collapse" style="display:inline-block;" class="replys" href="#reviewshow'+0+r+'" role="button" aria-expanded="false" aria-controls="reviewshow'+0+r+'"><img src="<?php echo e(asset('assets/images/reply.png')); ?>" width="15px"> '+level1[l1].children.length+' Reply </p>';
                                                         html+='<a href="#"  class="reply btnreply ms-2" data-review_id ="'+app[i].id+'" data-type="REVIEW" data-reply_id ="'+level1[l1].id+'">Reply</a>'; 
                                                         
                                                         //level 2 start
   
                                                            if(level2.length>0)
                                                            {
                                                               countl2 = 1;
                                                               for(l2 = 0;l2<level2.length;l2++)
                                                               {
                                                                  html+='<div class="collapse" id="reviewshow'+0+r+'">';
                                                                     html+='<div class="Allreply">';
                                                                     console.log(level2[l2].id);
   
                                                                     level3 = level2[l2].children;
   
                                                                     if(level2[l2].user.image)
                                                                     {
                                                                        html+='<figure><img src="'+level2[l2].user.image+'"></figure>';
                                                                  
                                                                     }else
                                                                     {
                                                                        html+='<figure><img src="<?php echo e(asset('assets/images/img-3.png')); ?>"></figure>';
                                                                     }
                                                                     html+='<div class="review-detail">';
                                                                        html+='<h6>'+level2[l2].user.name+'</h6>';
                                                                        html+='<p>'+level2[l2].message+'</p>';
                                                                     html+='<div/>';
   
                                                                     html+='<div/>';
                                                                     if(level2.length==countl2)
                                                                     {
                                                                        html+='<p data-bs-toggle="collapse" style="display:inline-block;" class="replys" href="#reviewshow'+1+r+'" role="button" aria-expanded="false" aria-controls="reviewshow'+1+r+'"><img src="<?php echo e(asset('assets/images/reply.png')); ?>" width="15px"> '+level2[l2].children.length+' Reply </p>';
                                                                        html+='<a href="#"  class="reply btnreply ms-2" data-review_id ="'+app[i].id+'" data-type="REVIEW" data-reply_id ="'+level2[l2].id+'">Reply</a>'; 
                                                                     }
                                                                     else
                                                                     {
                                                                        html+='<a href="#" class="reply btnreply ms-2" data-review_id ="'+app[i].id+'" data-type="REVIEW" data-reply_id ="'+level2[l2].id+'">Reply</a>';
                                                                     }
   
                                                                    // console.log("level3"+level3.length);
                                                                     //level 3 start
                                                                     if(level3.length>0)
                                                                     {
                                                                        countleve3=1;
                                                                        for(cl3 = 0; cl3<level3.length;cl3++)
                                                                        {
                                                                           html+='<div class="collapse" id="reviewshow'+1+r+'">';
                                                                           html+='<div class="Allreply">';
                                                                           if(level3[cl3].user.image)
                                                                           {
                                                                              html+='<figure><img src="'+level3[cl3].user.image+'"></figure>';
                                                                        
                                                                           }else
                                                                           {
                                                                              html+='<figure><img src="<?php echo e(asset('assets/images/img-3.png')); ?>"></figure>';
                                                                           }
   
                                                                              html+='<div class="review-detail">';
                                                                              html+='<h6>'+level3[cl3].user.name+'</h6>';
                                                                              html+='<p>'+level3[cl3].message+'</p>';
                                                                              html+='<a href="#" class="reply btnreply" data-review_id ="'+app[i].id+'" data-type="REVIEW" data-reply_id ="'+level3[cl3].id+'">Reply</a>';
                                                                              html+='</div>';
                                                                           html+='</div>';
                                                                           html+='</div>';
                                                                           countleve3++;
                                                                        }
                                                                      
                                                                     }
                                                                     //level 3 end
                                                                  html+='</div>';
                                                                  countl2 +=1;
                                                               }
   
                                                            }
                                                         // level 2 end
                                                          
                                                      }
                                                      else{
                                                         html+='<a href="#" class="reply btnreply" data-review_id ="'+app[i].id+'" data-type="REVIEW" data-reply_id ="'+level1[l1].id+'">Reply</a>';
   
                                                      }
                                                   countlevel++;
                                                 }
                                             }
                                            
                                         
                                          }
                                          
                              html+=' </div>';
                                    html+='<div/>';
                                    count++;
                                 }
                                  
                              }
                           //close reply 
                        html+='<div/>';
                        //reply
   
                     html+='<div/>';
                  html+='<div/>';
             html+='<div/>';  
             $("#communityReviewData").append(html);
            }
          
         }

         console.log("imgsrcClass-->"+ $('.imgsrcClass img').attr('src'));
         function fileexistsCheck(image,index)
         {
            imgsrc = "#imgsrc"+index;
       
            var headers = {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            host_url = "/development/wemarkthespot/";
               jQuery.ajax({
                  url: host_url+"fileexistsCheck",
                  type: "POST",
                  cache: false,
                  processData: false,
                  contentType: false,
                  data: {"image":image},
                  headers: headers,
                  success:function(data) {
                  var obj = JSON.parse(data);
                  $("#imgsrc0").attr("src",image);
                  if(obj.status==true)
                  {
                     $(imgsrc).attr("width","500");
                     $(imgsrc).attr("src",image);
                  }
                 else {
                  $(imgsrc).attr("width","500");
                     $(imgsrc).attr("src",image);
                  }
                     
               }
               });
         }
   });
    
    
</script>
         

<main class="community-review" >
   <div class="container-fluid">
      <h1 class="title">Community Reviews</h1>
      <div class="row gy-4">

        <div class="col-md-12" id="communityReviewData1">
            
        <?php
            // dd($BusinessReviews);  //test
        ?>
            
         <?php $__currentLoopData = $BusinessReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=> $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
         
         <?php
            // dd($user);  //test
         ?>
            <div class="BoxShade commutyReview">

                  <figure>
                     <?php if(!empty($user->user_image)): ?>
                     <img class="imgsrc" src="<?php echo e($user->user_image); ?>">
                     <?php else: ?>
                     <img class="imgsrc" src="<?php echo e(asset('/images/userimage.png')); ?>">                     
                     <?php endif; ?>
                  </figure>
                  <div class="user-Detail">
                     <h5><?php echo e($user->user_name); ?></h5>
                     <p class="r-date"> <?php echo e(date('d M Y', strtotime($user->created_at))); ?></p>
                     <ul class="share-up">
                        <?php 
                        $url = "community_reportweb/".$user->id."/".$user->id;
                        ?>
                      
                        <?php if($user->report=="1"): ?>
                        <li><a href="javascript:void(0)" style="cursor: no-drop">Reported</a></li>
                        <?php else: ?>
                        <li><a href="<?php echo $url;?>" data-business_id="<?php echo e($user->id); ?>" data-review_id="<?php echo e($user->id); ?>">Report</a></li>
                        <?php endif; ?>
                        <li class="clklikedislike" onclick="return likedislike('<?php echo e($user->id); ?>','<?php echo e($user->business_id); ?>','1')"  data-businessreview_id='<?php echo e($user->id); ?>' data-business_id='<?php echo e($user->business_id); ?>' data-like="1"><span class="icon-thumbs-up "  data-businessreview_id='<?php echo e($user->id); ?>' data-business_id='<?php echo e($user->business_id); ?>' data-llike="1"></span> <?php if($user->total_like): ?>  <?php echo e($user->total_like); ?> <?php else: ?> 0 <?php endif; ?> </li>

                        <li class="clklikedislike" onclick="return likedislike('<?php echo e($user->id); ?>','<?php echo e($user->business_id); ?>','2')"  data-businessreview_id='<?php echo e($user->id); ?>' data-business_id='<?php echo e($user->business_id); ?>' data-like="2"><span class="icon-thumbs-down "  data-businessreview_id='<?php echo e($user->id); ?>' data-business_id='<?php echo e($user->business_id); ?>' data-llike="1"></span> <?php if($user->total_dislike): ?>  <?php echo e($user->total_dislike); ?> <?php else: ?> 0 <?php endif; ?></li>
                     </ul>
                     
                     <p><?php echo e($user->review); ?></p>
                       <?php
                       if($user->image_video_status==1)
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
                                          <figure class="comment_img"><img alt="1" src="<?php echo e($imageArray[$img]); ?>"></figure>  
                                       <?php
                                       }
                                    }
                                 }
                              }
                           }
                           else
                           {
                              ?>
                              <figure class="comment_img"><img src="<?php echo e(asset('/images/userimage.png')); ?>"></figure>
                              <?php
                           }
                       }
                       else if($user->image_video_status==2)
                       {
                            $edited=  rtrim($user->image, ",");
                        ?>
                           <video controls  autoplay muted loop  class="comment_video"><source src="<?php echo $edited;?>" ></video>
                        <?php
                       }
                       ?> 
                     <div class="" style="display: inline-block;width: 100%;">
                       
                     <p class="Viewrepla" data-bs-toggle="collapse" href="#reviewshow<?php echo e($i); ?>" role="button" aria-expanded="false" aria-controls="reviewshow<?php echo e($i); ?>" style="display: inline-block;">&nbsp; </p><a href="#" class="reply btnreply" data-review_id ="<?php echo e($user->id); ?>" data-type="REVIEW" data-reply_id ="0">Reply</a>
                       
                  </div>
                   <div class="collapse" id="reviewshow<?php echo e($i); ?>">
                     <!--reply start-->
                       <?php
                        $repliesArray = $user->replies;
                        if(!empty($repliesArray))
                        {
                           $count = 1;
                           for($r=0;$r<count($repliesArray);$r++)
                           {
                              ?>
                              <div class="Allreply">
                                 <?php
                                    if($repliesArray[$r]['user']['image'])
                                    {
                                       ?>
                                       <figure><img src="<?php echo e($repliesArray[$r]['user']['image']); ?>"></figure>
                                       <?php
                                    }
                                    ?>
                                    <div class="review-detail">
                                       <h6><?php echo e($repliesArray[$r]['user']['name']); ?> </h6>
                                       <p><?php echo e($repliesArray[$r]['message']); ?></p>
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
                                                         if(!empty($imageArray[$img]))
                                                         {
                                                            ?>
                                                            <figure class="comment_img"><img alt="1" src="<?php echo e($imageArray[$img]); ?>"></figure>  
                                                         <?php
                                                         }
                                                      }
                                                   }
                                                }
                                             }
                                             else
                                             {
                                                ?>
                                                 <figure class="comment_img"><img src="<?php echo e(asset('/images/userimage.png')); ?>"></figure>
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

                                        if(count($repliesArray)==$count)
                                        {
                                          ?>
                                          <!-- <br> -->
                                          <div class="stylenextdiv">
                                          <p data-bs-toggle="collapse" style="display:inline-block;" class="replys " href="#reviewshow<?php echo e($r); ?>" role="button" aria-expanded="false" aria-controls="reviewshow<?php echo e($r); ?>"><img src="<?php echo e(asset('assets/images/reply.png')); ?>" width="15px"> <?php count($repliesArray[$r]['children']) ?> Reply</p>
                                          <a href="#"  class="reply btnreply ms-2" data-review_id ="<?php echo e($user->id); ?>" data-type="REVIEW" data-reply_id ="<?php echo e($repliesArray[$r]['id']); ?>">Reply</a>
                                          </div>
<?php
                                        }
                                        else
                                        {
                                           ?>
                                           <p><div class="d-block"><a href="#"  class="reply btnreply ms-2" data-review_id ="<?php echo e($user->id); ?>" data-type="REVIEW" data-reply_id ="<?php echo e($repliesArray[$r]['id']); ?>">Reply</a></div></p>
                                           <?php
                                        }
                                         //end level 1
                                        // if(count($repliesArray)==$count)  //This is the issue //RR
                                        if($count > 0)
                                         {
                                          $level1 = $repliesArray[$r]['children'];
                                          // echo count($level1);  //test  OK //RR
                                             if(count($level1)>0)
                                             {
                                                $countlevel=1;
                                                for($l1=0;$l1<count($level1);$l1++)
                                                {
                                                   $level2 = $level1[$l1]['children'];
                                                   
                                                    // echo $level1[$l1]['user']['image'];  //test
                                                    // echo $level1[$l1]['message'];  //test
                                                   ?>
                                                   <div class="collapse subthreads" id="reviewshow<?php echo e($r); ?>" style="display:block;">  <!-- id ka issue hai pehle <?php echo e($r); ?> tha -->
                                                      <div class="Allreply">
                                                         <?php
                                                            
                                                            if(!empty($level1[$l1]['user']['image']))
                                                            {
                                                               ?>
                                                               <figure><img src="<?php echo e($level1[$l1]['user']['image']); ?>"></figure>
                                                               <?php
                                                            }
                                                            else
                                                            {
                                                               ?>
                                                               <figure><img src="<?php echo e(asset('/images/userimage.png')); ?>"></figure>
                                                               <?php
                                                            }
                                                         ?>
                                                         <div class="review-detail">
                                                            
                                                            <?php 
                                                                // echo "YHAA--" . $l1;
                                                                // echo $level1[$l1]['message'];
                                                            ?>
                                                            
                                                            <h6><?php echo e($level1[$l1]['user']['name']); ?> </h6>
                                                            <p><?php echo e($level1[$l1]['message']); ?></p>
                                                
                                                            <?php
                                                               if($level1[$l1]['video_image_status']== 1)
                                                               {
                                                                  $imageArray = explode(",",$level1[$l1]['image']);
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
                                                                                 <figure class="comment_img"><img alt="1" src="<?php echo e($imageArray[$img]); ?>"></figure>  
                                                                              <?php
                                                                              }
                                                                           }
                                                                        }
                                                                     }

                                                                  }
                                                                  else
                                                                     {
                                                                        ?>
                                                                        <figure class="comment_img"><img src="<?php echo e(asset('/images/userimage.png')); ?>"></figure>
                                                                        <?php
                                                                     }
                                                               }
                                                               else if($level1[$l1]['video_image_status']==2)
                                                               {
                                                                  $edited=  rtrim($level1[$l1]['image'], ",");
                                                                  ?>
                                                                  <video controls  autoplay muted loop  class="comment_video"><source src="<?php echo $edited;?>" ></video>
                                                               <?php
                                                               }
                                                            ?>
                                                         </div>
                                                      
                                                         <?php
                                                            // if(count($level1) == $countlevel)
                                                            if(count($level1) > 0)
                                                            {
                                                               ?>
                                                               
                                                               <p data-bs-toggle="collapse" style="display:inline-block;" class="replys" href="#reviewshow0<?php echo e($r); ?>" role="button" aria-expanded="false" aria-controls="reviewshow0<?php echo e($r); ?>"><img src="<?php echo e(asset('assets/images/reply.png')); ?>" width="15px"> <?php echo e(count($level1[$l1]['children'])); ?> Reply </p>
                                                               <a href="#"  class="reply btnreply ms-2" data-review_id ="<?php echo e($user->id); ?>" data-type="REVIEW" data-reply_id ="<?php echo e($level1[$l1]['id']); ?>">Reply</a>
                                                               <?php

                                                               if(count($level2)>0)
                                                               {
                                                                  $countl2 = 1;
                                                                  for($l2 = 0;$l2<count($level2);$l2++)
                                                                  {
                                                                     ?>
                                                                     <div class="collapse" id="reviewshow0'<?php echo e($r); ?>" style="display:block;">    <!-- Change id from <?php echo e($r); ?> to <?php echo e($l2); ?> -->
                                                                        <div class="Allreply">
                                                                           <?php
                                                                           $level3 = $level2[$l2]['children'];
                                                                           if($level2[$l2]['user']['image'])
                                                                           {
                                                                              ?>
                                                                              <figure><img src="<?php echo e($level2[$l2]['user']['image']); ?>"></figure>
                                                                              <?php
                                                                           }
                                                                           else
                                                                           {
                                                                                 ?>
                                                                                 <figure><img src="<?php echo e(asset('/images/userimage.png')); ?>"></figure>
                                                                                 <?php
                                                                           }
                                                                           ?>
                                                                           <div class="review-detail">
                                                                           <h6><?php echo e($level2[$l2]['user']['name']); ?> </h6>
                                                                           <p><?php echo e($level2[$l2]['message']); ?></p>
                                                                           <?php
                                                               if($level2[$l2]['video_image_status']==1)
                                                               {
                                                                  $imageArray2 = explode(",",$level2[$l2]['image']);
                                                                  if(count($imageArray2)>0)
                                                                  {
                                                                     for($img2 =0; $img2<count($imageArray2);$img2++)
                                                                     {
                                                                        $path_parts2 = pathinfo($imageArray2[$img2]);
                                                                        if(!empty($path_parts2['extension']))
                                                                        {
                                                                           $extension2 =$path_parts2['extension']; 
                                                                           if($extension2=="png" || $extension2=="jpg" || $extension2=="jpeg" || $extension2=="gif" || $extension2=="svg")
                                                                           {
                                                                              if(!empty($imageArray2[$img2]))
                                                                              {
                                                                                 ?>
                                                                                 <figure class="comment_img"><img alt="1" src="<?php echo e($imageArray2[$img2]); ?>"></figure>  
                                                                              <?php
                                                                              }
                                                                           }
                                                                        }
                                                                     }

                                                                  }
                                                                  else
                                                                     {
                                                                        ?>
                                                                        <figure class="comment_img"><img src="<?php echo e(asset('/images/userimage.png')); ?>"></figure>
                                                                        <?php
                                                                     }
                                                               }
                                                               else if($level2[$l2]['video_image_status']==2)
                                                               {
                                                                  $edited2=  rtrim($level2[$l2]['image'], ",");
                                                                  ?>
                                                                  <video controls  autoplay muted loop  class="comment_video"><source src="<?php echo $edited2;?>" ></video>
                                                               <?php
                                                               }
                                                            ?>
                                                                           </div>
                                                                        </div>
                                                                            <?php
                                                                            
                                                                            if(count($level2) == $countl2)
                                                                            {
                                                                              ?>
                                                                              <p data-bs-toggle="collapse" style="display:inline-block;" class="replys" href="#reviewshow1<?php echo e($r); ?>" role="button" aria-expanded="false" aria-controls="reviewshow1<?php echo e($r); ?>"><img src="<?php echo e(asset('assets/images/reply.png')); ?>" width="15px"> <?php echo e(count($level2[$l2]['children'])); ?>&nbsp;Reply </p>
                                                                              <a href="#"  class="reply btnreply ms-2" data-review_id ="<?php echo e($user->id); ?>" data-type="REVIEW" data-reply_id ="<?php echo e($level2[$l2]['id']); ?>">Reply</a>
                                                                              <?php
                                                                            }
                                                                            else
                                                                            {
                                                                               ?>
                                                                        <a href="#" class="reply btnreply ms-2" data-review_id ="<?php echo e($user->id); ?>" data-type="REVIEW" data-reply_id ="<?php echo e($level2[$l2]['id']); ?>">Reply</a>
                                                                           <?php }
                                                                           //level 3 start
                                                                              if(count($level3)>0)
                                                                              {
                                                                                 // $level4 = "";  //Added New //RR
                                                                                 $countleve3 = 1;
                                                                                 for($cl3 = 0 ; $cl3< count($level3);$cl3++)
                                                                                 {
                                                                                    // $level4 = $level3[$cl3]['children']; //Added New for level 4
                                                                                    
                                                                                    ?>
                                                                                    <div class="collapse" id="reviewshow1<?php echo e($r); ?>" style="display:block;">  <!-- Change id from <?php echo e($r); ?> to <?php echo e($cl3); ?> -->
                                                                                       <div class="Allreply">
                                                                                          <?php
                                                                                          
                                                                                            $level4 = $level3[$cl3]['children']; //Added New for level 4

                                                                                             if($level3[$cl3]['user']['image'])
                                                                                             {
                                                                                                ?>
                                                                                                <figure><img src="<?php echo e($level3[$cl3]['user']['image']); ?>"></figure>
                                                                                                <?php
                                                                                             }
                                                                                             else
                                                                                             {
                                                                                                ?>
                                                                                                <figure><img src="<?php echo e(asset('/images/userimage.png')); ?>"></figure>
                                                                                                <?php
                                                                                             }
                                                                                          ?>
                                                                                          <div class="review-detail">
                                                                                             <h6><?php echo e($level3[$cl3]['user']['name']); ?></h6>
                                                                                             <p><?php echo e($level3[$cl3]['message']); ?></p>
                                                                                             <?php
                                                               if($level3[$cl3]['video_image_status']==1)
                                                               {
                                                                  $imageArray3 = explode(",",$level3[$cl3]['image']);
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
                                                                              if(!empty($imageArray3[$img3]))
                                                                              {
                                                                                 ?>
                                                                                 <figure class="comment_img"><img alt="1" src="<?php echo e($imageArray3[$img3]); ?>"></figure>  
                                                                              <?php
                                                                              }
                                                                           }
                                                                        }
                                                                     }

                                                                  }
                                                                  else
                                                                     {
                                                                        ?>
                                                                        <figure class="comment_img"><img src="<?php echo e(asset('/images/userimage.png')); ?>"></figure>
                                                                        <?php
                                                                     }
                                                               }
                                                               else if($level3[$cl3]['video_image_status']==2)
                                                               {
                                                                  $edited3=  rtrim($level3[$cl3]['image'], ",");
                                                                  ?>
                                                                  <video controls  autoplay muted loop  class="comment_video"><source src="<?php echo $edited3;?>" ></video>
                                                               <?php
                                                               }
                                                            ?>
                                                                                             <!--<a href="#" class="reply btnreply" data-review_id ="<?php echo e($user->id); ?>" data-type="REVIEW" data-reply_id ="<?php echo e($level3[$cl3]['id']); ?>">Reply</a>-->
                                                                                          </div>
                                                                                       </div>
                                                                                    </div>
                                                                                    <?php
                                                                                 }
                                                                                 $countleve3++;
                                                                              }
                                                                           //level 3 end

                                                                            ?>  


                                                                    <!-- === Lavel 4 Start ========================================= -->
                                                                            <?php
                                                                            //level 4 start
                                                                            // $level4 = isset($level4)?$level4:1;  //New Added
                                                                              if(isset($level4))
                                                                              {
                                                                                 $countleve4 = 1;
                                                                                 for($cl4 = 0 ; $cl4< count($level4);$cl4++)
                                                                                 {
                                                                                    ?>
                                                                                    <div class="collapse" id="reviewshow1<?php echo e($r); ?>" style="display:block;">  <!-- Change id from <?php echo e($r); ?> to <?php echo e($cl3); ?> -->
                                                                                       <div class="Allreply">
                                                                                          <?php
                                                                                             if($level4[$cl4]['user']['image'])
                                                                                             {
                                                                                                ?>
                                                                                                <figure><img src="<?php echo e($level4[$cl4]['user']['image']); ?>"></figure>
                                                                                                <?php
                                                                                             }
                                                                                             else
                                                                                             {
                                                                                                ?>
                                                                                                <figure><img src="<?php echo e(asset('/images/userimage.png')); ?>"></figure>
                                                                                                <?php
                                                                                             }
                                                                                          ?>
                                                                                          <div class="review-detail">
                                                                                             <h6><?php echo e($level4[$cl4]['user']['name']); ?></h6>
                                                                                             <p><?php echo e($level4[$cl4]['message']); ?></p>
                                                                                             <?php
                                                               if($level4[$cl4]['video_image_status']==1)
                                                               {
                                                                  $imageArray4 = explode(",",$level4[$cl4]['image']);
                                                                  if(count($imageArray4)>0)
                                                                  {
                                                                     for($img4 =0; $img4<count($imageArray4);$img4++)
                                                                     {
                                                                        $path_parts3 = pathinfo($imageArray4[$img4]);
                                                                        if(!empty($path_parts3['extension']))
                                                                        {
                                                                           $extension3 =$path_parts3['extension']; 
                                                                           if($extension3=="png" || $extension3=="jpg" || $extension3=="jpeg" || $extension3=="gif" || $extension3=="svg")
                                                                           {
                                                                              if(!empty($imageArray4[$img4]))
                                                                              {
                                                                                 ?>
                                                                                 <figure class="comment_img"><img alt="1" src="<?php echo e($imageArray4[$img4]); ?>"></figure>  
                                                                              <?php
                                                                              }
                                                                           }
                                                                        }
                                                                     }

                                                                  }
                                                                  else
                                                                     {
                                                                        ?>
                                                                        <figure class="comment_img"><img src="<?php echo e(asset('/images/userimage.png')); ?>"></figure>
                                                                        <?php
                                                                     }
                                                               }
                                                               else if($level4[$cl4]['video_image_status']==2)
                                                               {
                                                                  $edited3 = rtrim($level4[$cl4]['image'], ",");
                                                                  ?>
                                                                  <video controls  autoplay muted loop  class="comment_video"><source src="<?php echo $edited3;?>" ></video>
                                                               <?php
                                                               }
                                                            ?>
                                                                                             <!--<a href="#" class="reply btnreply" data-review_id ="<?php echo e($user->id); ?>" data-type="REVIEW" data-reply_id ="<?php echo e($level4[$cl4]['id']); ?>">Reply</a>-->
                                                                                          </div>
                                                                                       </div>
                                                                                    </div>
                                                                                    <?php
                                                                                    
                                                                                    $countleve4++;
                                                                                 }
                                                                                //  $countleve4++;
                                                                              }
                                                                           //level 3 end

                                                                            ?>  
                                                                    <!-- === --Level 4 End ========================================= -->

                                                                     </div>
                                                                     <?php
                                                                     $countl2 ++;
                                                                  }
                                                                    // $countl2 ++;
                                                               }
                                                            }
                                                            else
                                                            {
                                                               ?>
                                                               <a href="#" class="reply btnreply" data-review_id ="<?php echo e($user->id); ?>" data-type="REVIEW" data-reply_id ="<?php echo e($level1[$l1]['id']); ?>">Reply</a>
                                                               <?php
                                                            }
                                                            ?> 
                                                      </div>
                                                   </div>
                                                   <?php
                                                   $countlevel++;
                                                }
                                                // $countlevel++;
                                             }
                                         
                                         }
                                       ?>
                                    </div>
                              </div>
                              <?php
                              $count++;
                           }
                        //   $count++;
                        }
                       ?>
                     <!--reply end-->
                   </div>
                  </div>
               </div>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              
               <?php echo e($BusinessReviews->links()); ?>

         </div>

         <div class="row gy-4">
               <div class="col-md-12" style="">
               </div>
         </div>
         <div class="col-md-12" style="display:">
            <nav aria-label="...">

            </nav>
         </div>
      </div>
   </div>
</main>


<!-- Modal -->
<div class="modal fade ReplyModel" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Reply</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form id="replyform" action="javascript(0)" method="POST">
            <div class="result"></div>
            <div class="modal-body">
               <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>"/>
               <input type="hidden" id="type" name="type" value=""/>
               <input type="hidden" id="review_id" name="review_id" value=""/>
               <input type="hidden" id="reply_id" name="reply_id" value=""/>
               <textarea class="form-control" id="message" name="message" ></textarea>
            </div>
            <div class="modal-footer">
               <input type="submit" class="btn btn-primary" value="Reply"/> 
            </div>
         </form>
      </div>
   </div>
</div>
<?php echo $__env->make("inc/footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script type="text/javascript">
   $(document).ready(function(e) {
     $("#replyform").validate({
   rules: {
   message: {required: true,},
   
   },
   
   messages: {
   message: {required: "Please enter reply",},
   },
   submitHandler: function(form) {
     var formData= new FormData(jQuery('#replyform')[0]);
           host_url = "/development/wemarkthespot/";
   jQuery.ajax({
      url: host_url+"replyform",
      type: "POST",
      cache: false,
      data: formData,
      processData: false,
      contentType: false,
      
      success:function(data) { 
      
      var obj = JSON.parse(data);
      
      if(obj.status==true){
         jQuery('#name_error').html('');
         jQuery('#email_error').html('');
         jQuery('.result').html("<div class='alert alert-success alert-dismissible text-white border-0 fade show' role='alert'><button type='button' class='btn-close btn-close-white' data-bs-dismiss='alert' aria-label='Close'></button><strong style='color:red'>"+obj.message+"</strong> </div>");
         setTimeout(function(){
            jQuery('.result').html('');
            window.location = host_url+"community-reviews";
         }, 2000);
      }
      else{
         if(obj.status==false){
            jQuery('.result').html(obj.message);
            jQuery('#name_error').css("display", "block");
         }
         
      }
      }
   });
   }
   });
   
     $(".btnreply").on('click',function(){
        
        type =$(this).data("type");
        review_id = $(this).data('review_id');
        reply_id = $(this).data('reply_id');
        $("#reply_id").val(reply_id);
        $("#review_id").val(review_id);
        $("#type").val(type);
   
        $(".ReplyModel").modal('show');
   
        //alert("review_id"+review_id +"reply_id"+reply_id);
     })
    
     $(".nav-item a").removeClass("active");
     $("#community-reviews").addClass('active');
   });
</script>
<style>
   .commutyReview figure img {
   border-radius: 8%;
   }
</style>

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
    /* display: inline-block; */
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
a.relative.inline-flex.items-center svg {
    margin-right: 8px;
}
.relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.cursor-default.leading-5 {
    display: block;
}
a.relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-gray-700.bg-white.border.border-gray-300.leading-5.hover\:text-gray-500.focus\:z-10.focus\:outline-none.focus\:ring.ring-gray-300.focus\:border-blue-300.active\:bg-gray-100.active\:text-gray-700.transition.ease-in-out.duration-150 {
    display: block;
}

 </style>
<?php /**PATH C:\xampp\htdocs\development\wemarkthespot\resources\views/wemarkthespot/community-reviews.blade.php ENDPATH**/ ?>