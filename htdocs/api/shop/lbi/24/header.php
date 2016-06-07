<div class="nav">
		 <div class="container">
			<div class="row">  
      	
        	<div class="col-xs-2 header-icon">  
            <a href="/api/<?=$UsersID?>/shop/"><span class="fa fa-comments-o white fa-2x"></span></a>            
		  	
		</div>
	
          <div class="col-xs-8">
	  <form id="shop_search" action="<?=$base_url?>/api/shop/search.php" />
      	<input type="hidden" name="UsersID" value="vt8ugoicy6"/>
      	<input type="hidden" name="search" value="1"/>
      	<input type="text" class="form-control" name="kw" id="nav-keyword" aria-describedby="inputSuccess3Status" notnull>
      	<span  id="search_btn" class="fa fa-search  nav-search-icon grey" aria-hidden="true"></span>
        </form>  
        </div>
		  
      		<div  class="col-xs-2 header-icon ">
       		<a href="/api/<?=$UsersID?>/shop/category/"><span class="fa fa-reorder white fa-2x"></span></a>
     	 </div>
        	
 	    
        </div>
		
         </div>
	</div>