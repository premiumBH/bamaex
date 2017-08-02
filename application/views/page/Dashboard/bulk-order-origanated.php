		        <?php

        $this->load->view('layout/header');

        $this->load->view('layout/container');

		?>

        <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">

                        <div class="form-group">
                         <input class="btn green" type="file" name="Template" value="upload CSV">
                         </div>

                         
            </div>    
             <div class="col-md-6">

                        <div class="col-md-3">
                            <div class="form-group">
                             <input class="btn green" value="View File" type="submit" name="submit">
                             </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                             <a href="<?php echo base_url();?>dashboard\download_template" download="Template.xlsx"><input class="btn green" value="Download Template" type="button"></a>
                             </div>
                        </div>     
            </div>   
         </div>
        </form>

<div class="portlet-body">
    <div class="table-responsive">
        <table class="table">

            <thead>

                <tr>

                    <th>S.No. </th>

                    <th>Full Name</th>

                    <th> Email</th>

                    <th> Mobile </th>

                    <th> User Level </th>

                    <th> Created On</th>

                </tr>

            </thead>

           </table>
    </div>
</div>

            
       









<?php
        $this->load->view('layout/footer');   

        ?>

