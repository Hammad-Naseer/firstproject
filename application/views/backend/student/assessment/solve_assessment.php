<link href='https://fonts.googleapis.com/css?family=Sofia' rel='stylesheet'>
<link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet'>
<link href='https://fonts.googleapis.com/css?family=Allerta Stencil' rel='stylesheet'>
<style>
    body {
        font-family: 'ABeeZee';
    }
	.bstdown{
		position: absolute;
		overflow:hidden;
 		background-color:#000;
		color:#FFF;
		font-size:15px;
		padding:10px;
		cursor:pointer;
		visibility:hidden;
	    width: 57%;
    	left: 38px;	
	}
	.bst{
		cursor:pointer;
	}
    
    @media (max-width: 768px){
        .school-logo {
            display:none;
        }
        .user-info {
            margin-top:14px;
        }
    }
    /* _____________________________________*/
</style> 

<?php 

    $_SESSION['school_name'] = "";
    $qurr = $this->db->query("select * from ".get_school_db().".school where  school_id=".$_SESSION['school_id'])->result_array();
    $_SESSION['school_name'] = $qurr[0]['name'];
    $_SESSION['school_logo'] = $qurr[0]['logo'];
    $_SESSION['folder_name'] = $qurr[0]['folder_name'];
    $system_name = $_SESSION['school_name'];
    $system_title= $_SESSION['school_name'];
    
    $account_type= $_SESSION['login_type']; //get_login_type_folder($_SESSION['login_type']);
    $res = $this->db->query("select * from ".get_system_db().".user_login where  user_login_id=".$_SESSION['user_login_id'])->result_array();
?>

<!DOCTYPE html>
<html>
	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo get_phrase('Indici-Edu'); ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="" />
		<meta name="author" content="Creativeitem" />
		<?php include 'top.php';?>
	</head>
	<body class="page-body" >
    <?php $this->load->view("backend/top"); ?>
    <script src="https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js"></script>
    <script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>

    <style>
        .container_self
        {
            width: 95%;
            margin-top: 10px;
        }
        .panel_heading_self
        {
            height: 50px;
            padding: 10px 0px 5px 10px;
            font-size: 18px;
        }
        .panel_self
        {
            color: black;
        }
        .timer_heading{
            font-size: 30px !important;
        font-weight: 600;
        letter-spacing: 6px;
        }
        .self_timer
        {
            /*float: right;*/
            /*height: auto;*/
            /*width: auto;*/
            /*background: #3498c6;*/
            /*padding: 5px 5px 5px 5px;*/
            /*margin-right: 10px;*/
            /*border-radius: 0px;*/
            /*font-weight: 900;*/
            /*text-align:center;*/
            /*border-left: 4px solid #3498c6;*/
            /*border-bottom: 4px solid #3498c6;*/
            /*margin-bottom: 20px;*/
        }
        #demo
        {
            font-size:30px !important;
            color:black;
            font-variant: petite-caps;
            font-weight:900;
        }
        .card {
          box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
          transition: 0.3s;
          width: 100%;
        }
        
        .card:hover {
          box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }
        
        .container_after_timer {
          padding: 2px 16px;
        }
        .detail_card_p{
            font-size: 14px !important;
            margin:0px 0px 0px 0.5px;
        }
        #sidebars { 
          /*width: 190px; */
          position: fixed; 
          margin: 30px 0 0 130px; 
          box-shadow: 1px 2px 4px 2px #cccccc94;
        }
        .sidenav_assessment {
          z-index: 1;
          top: 4%;
          left: 65%;
          background: #eee;
          overflow-x: hidden;
          padding: 15px 15px;
        }
        /* Header/Logo Title */
        .header_assess {
          padding: 20px;
          text-align: center;
          background: #139bda;
          color: white;
          font-size: 30px;
        }
        .Q{
            font-size:20px;
        }
        .panel{
            border: 3px solid transparent;
            box-shadow: 1px 2px 10px 1px #ccc;
        }
        .panel-info {
            border-color: #000000;
        }
        #zwibbler_11{
            width:100% !important;
        }
        .times{
            background: black;
            color: white;
            width: 120px !important;
            border-radius: 10px;
            font-size: 60px !important;
            text-align: -webkit-center;
            padding: 7px;
            /*font-family: 'Orbitron';*/
            font-family: 'Allerta Stencil';
        }
        .times_text{
            font-size: 18px !important;
            position: relative;
            top: 0px;
            text-align: -webkit-center;
            font-family: 'Orbitron';
        }
        
        .voice_over{
            width: 50px;
            height: 50px;
            border-radius: 30px;
            background: #f44336;
            border: 1px solid red;
            color: white;
            font-size: 20px;
            text-align: center;
            /*position: relative;*/
            outline: none;
            float: left;
            /*top: -6px;*/
            /*left: -6px;*/
            position: absolute;
            left: -24px;
            top: -18px;
            border: 8px solid white;
        }
        .voice_over:focus{
            outline:none;
        }
        .wrs_tickContainer{
            display:none;
        }
        .wrs_modal_dialogContainer{
            z-index: 999999999 !important;
            top: 30%;
            left: 35%;
        }
        zwibbler {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  display: flex;
  flex-flow: row nowrap;
}

.tools {
  background: #f5f5f5;
  flex: 0 0 203px;
  display:flex;
  flex-flow: column nowrap;
  overflow-y: scroll;
  padding: 10px;
  font-family: Ubuntu;
}

[z-canvas] {
  flex: 1 1 auto;
}

.tools button {
  font-family: inherit;
  font-size: 100%;
  padding: 5px;
  display: block;
  background-color: white;
  border: none;
  border-radius: 2px;
  border-bottom: 2px solid #ddd;
  width: 100%;
}

.tools button[tool] {
  display: inline-block;
  width: 60px;
  height: 60px;
  font-size: 30px;
}

.tools button.option {
  border: 0;
  padding: 10px;
  border-radius: 3px;
  background: transparent;
  text-align: left;
}

.tools button.selected {
  background: #dbe6d7;
}

.tools button.hover {
  background: #ddd;
}

.tools hr {
  border: none;
  border-top: 1px solid #ccc;
}

.tools select {
  width: 100%;
}

[swatch] {
  border: 1px solid black;
  display: inline-block;
  height: 2em;
  width: 4em;
  vertical-align:middle;
  margin-right: 10px;
}

.colour-picker {
  padding: 10px 0;
}

.pages {
  flex: 0 0 100px;
  background: #ccc;
  display: flex;
  flex-flow: row nowrap;
  overflow-x: scroll;
  overflow-y: hidden;
  align-items: center;
}

.page {
  border: 3px solid transparent;
  margin: 5px;
  display: inline-block;
  box-shadow: 2px 2px 2px rgba(0,0,0,0.2);
}

.page.selected {
  border: 3px solid orange;
}

[z-popup] {
  background: #ccc;
  padding: 10px;
  box-shadow: 2px 2px 2px rgba(0,0,0.2);
}

    </style>
    <script src="https://zwibbler.com/zwibbler-demo.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Bitter:ital,wght@0,400;0,700;1,400;1,700&family=East+Sea+Dokdo&family=Goldman:wght@400;700&family=Hanalei+Fill&family=Kufam:ital,wght@0,400;0,700;1,700&family=Pirata+One&family=Poppins:ital,wght@0,400;0,700;1,400;1,700&family=Raleway:ital,wght@0,400;0,700;1,400;1,700&family=Redressed&family=Roboto+Condensed:ital,wght@0,400;0,700;1,400;1,700&family=Slabo+27px&family=Yeon+Sung&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css2?family=Alice&family=Lustria&family=Spectral&family=Spectral+SC&display=swap" rel="stylesheet"> 
    <?php $assessment_row = get_assessment_row($assessments[0]['assessment_id']); ?>
    <div class="header_assess">
      <h1 style="color:white !important;"><?php echo $assessment_row->assessment_title; ?></h1>
    </div>
    <div class="container-fluid" style="position:relative">
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <form id="answer_sheet" action="<?php echo base_url().'assessment_student/submit_assessment'?>" method="POST" enctype="multipart/form-data">
                <br><br>
                <input type="hidden" name="assessment_id" id = "assessment_id" value="<?php echo $assessments[0]['assessment_id'];?>" >
                <div class="container container_self">
                <div class="panel panel-info panel_self">
                <?php if($assessment_row == null){  exit; } ?>
                <?php
                $count = 0;
                $zwibbler_found = 0;
                $zwibbler_index = 0;
                foreach($assessments as $question)
                {
                    $count++;
                ?>
                <input type="hidden" id="question_ids_<?php echo $count ?>"        name="question_ids_<?php echo $count ?>" value="<?php echo $question['question_id'] ?>">
                <input type="hidden" id="question_type_ids_<?php echo $count ?>"   name="question_type_ids_<?php echo $count ?>" value="<?php echo $question['question_type_id'] ?>">
    
            <div class="panel-body">
                <?php
                if($question['question_type_id'] == 3)
                {
                 ?>      
                    <b class="Q">Q # <?php echo $count;?> : </b>
                    
                 <?php 
                    $answer_input = '<input type="text" style="width: 25%;display: inherit;" id="answer_'.$count.'" name="answer_'.$count.'" class="form-control" placeholder="Write Answer Here">';
                    $altered_question_text = str_replace('_', $answer_input, $question['question_text']);
                    echo "<b><span class='".$count."'>".$altered_question_text."</span></b>";
                 ?>
                     <button type="button" onclick="speak('span.<?php echo $count ?>')" class="voice_over">
                         <i class="fas fa-microphone"></i>
                     </button>
                 <?php
                }
                else
                {
                ?>
                
                    <strong class="Q">Q # <?php echo $count ?>: <span class="<?php echo $count ?>"> <?php echo $question['question_text'] ?> </span> </strong>
                    <button type="button" onclick="speak('span.<?php echo $count ?>')" class="voice_over">
                        <i class="fas fa-microphone"></i>
                    </button>
                <?php 
                } 
                ?>
                  <button type="button" class="btn btn-primary btn-sm" style="float:right;"><b>Total Marks : <?php echo $question['question_total_marks'];?></b></button>
                  
                  <br>
                  <br>
                  <?php
                  if($question['question_type_id'] == 1) // mcqs and fill in the blanks
                  {
                      $question_options = get_question_options($question['question_id']);
                      $j = 0;
                      foreach($question_options as $options)
                      {
                          $j++;
                    ?>
                        <label class="radio-inline">
                            <input type="radio" id="answer_<?php echo $count.'_'.$j ?>" value="<?php echo $options['option_number']?>" name="answer_<?php echo $count; ?>"><?php echo $options['option_text']; ?>
                        </label>
                    <?php
                      }
                      echo "<hr>";
                  }elseif($question['question_type_id'] == 2) // true/false
                  {
                    ?>
                        <label class="radio-inline">
                            <input type="radio" id="answer_<?php echo $count.'_'.$j ?>" value="true" name="answer_<?php echo $count; ?>">True
                        </label>
                        
                        <label class="radio-inline">
                            <input type="radio" id="answer_<?php echo $count.'_'.$j ?>" value="false" name="answer_<?php echo $count; ?>">False
                        </label>
                    <?php 
                    echo "<hr>";
                  }elseif($question['question_type_id'] == 4 || $question['question_type_id'] == 5)  //short and long
                  {
                    ?>
                        <div class="form-group">
                            <label>Please type your answer here.</label>
                            <textarea class="editor" id="answer_<?php echo $count ?>" name="answer_<?php echo $count; ?>" ></textarea>
                        </div>
                <?php
                    echo "<hr>";
                  }elseif($question['question_type_id'] == 6 )  //pictorial questions
                  {
                    ?>
                        <div class="text-center">
                            <img style="padding:10px;" width="70%" src="<?php echo base_url() ."uploads/".$_SESSION['folder_name']."/pictorial_question/".$question['image_url']; ?>" />
                        </div>
                        <div class="form-group">
                            <label>Please type your answer here.</label>
                            <textarea class="editor" id="answer_<?php echo $count ?>" name="answer_<?php echo $count; ?>" ></textarea>
                        </div>
                <?php
                    echo "<hr>";
                  }elseif($question['question_type_id'] == 7 )  //matching questions
                  {
                      
                        $matching_question_option = get_matching_question_option($question['question_id']);
                    ?>
                       
                        <div class="form-group">
                            <table class="table table-bordered">
                                <thead>
                                    <th>Column A</th>
                                    <th>Options</th>
                                    <th>Column B</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $x = 0;
                                    $total_option = count($matching_question_option);
                                    
                                    foreach($matching_question_option as $matching_questions)
                                    {
                                        
                                        $x++;
                                    ?>
                                        <tr>
                                            <input type="hidden" name="matching_question_option_id_<?php echo $count; ?>[]" value = "<?php echo $matching_questions['matching_question_option_id']; ?>"/>
                                            <td>
                                                <?php echo $matching_questions['left_side_text']; ?>
                                            </td>
                                            <td>
                                                
                                                <select name="answermatching_<?php echo $count; ?>[]" class="form-control">
                                                        <option value="">Select Right Option</option>
                                                    <?php
                                                    foreach($matching_question_option as $a)
                                                    {
                                                        
                                                    ?>
                                                        <option id="answermatching_<?php echo $count.'_'.$a['option_number'] ?>"  value="<?php echo $a['option_number'];?>">
                                                            <?php echo $a['right_side_text'];?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <span><?php echo $x; ?> . </span>
                                                <?php echo $matching_questions['right_side_text']; ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        
                        
                <?php
                    echo "<hr>";
                  }elseif($question['question_type_id'] == 8 )  //Drawing questions
                  {
                      $zwibbler_found++;
                        
                    ?>
                    <style>
                        .zwibbler-main{
                            width:100% !important;
                        }
                    </style>
                    <span style="color: red;text-align: center;float: right;" id="zwibbler_span_<?php echo $count; ?>"></span>
                    <div id="zwibbler_div_<?php echo $count; ?>" style="width:100% !important">
                        <div class="form-group">
                            <label>Please type your answer here.</label>
                            <input type="hidden" id="zwibbler_index_<?php echo $count; ?>" value="<?php echo $zwibbler_index; ?>"/>
                            <input type="hidden" id="zwibbler_question_<?php echo $count; ?>" value="<?php echo $question['question_id']; ?>"/>
                            <!--<div id="zwibbler_<?php echo $count; ?>" class="drawingtool zwibblerabc" style="margin-left:auto;margin-right:auto;border:2px solid red;width:800px;height:600px;"></div>-->
                            <zwibbler id="zwibbler_<?php echo $count; ?>" class="drawingtools zwibblerabc" showToolbar="false" pageView="false" showDebug="false" allowTextInShape="false" z-init="filename='drawing'" style="height:720px;position:relative;top:50%;">
                              <div class="tools">
                                <div>
                                  <button tool z-click="ctx.usePickTool()" title="Select" z-selected="ctx.getCurrentTool()=='pick'">
                                    <i class="fas fa-mouse-pointer"></i>
                                  </button>
                                  <button tool z-click="ctx.useBrushTool()" title="Draw" z-class="{selected:ctx.getCurrentTool()=='brush'}">
                                    <i class="fas fa-pencil-alt"></i>
                                  </button>
                                  <button tool z-click="ctx.useLineTool()" title="Lines" z-class="{selected:ctx.getCurrentTool()=='line'}">
                                    <i class="fas fa-draw-polygon"></i>
                                  </button>
                                  <button tool z-click="ctx.useRectangleTool()" title="Rectangle" z-class="{selected:ctx.getCurrentTool()=='rectangle'}">
                                    <i class="fas fa-square"></i>
                                  </button>
                                  <button tool z-click="ctx.useCircleTool()" title="Circle" z-class="{selected:ctx.getCurrentTool()=='circle'}">
                                    <i class="fas fa-circle"></i>
                                  </button>
                                  <button tool z-click="ctx.useShapeTool('SvgNode', {fillMode:'custom',width:200,url:'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaWQ9IkxheWVyXzFfMV8iIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDE2IDE2OyIgdmVyc2lvbj0iMS4xIiB2aWV3Qm94PSIwIDAgMTYgMTYiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxwYXRoIGQ9Ik04LjYxMiwyLjM0N0w4LDIuOTk3bC0wLjYxMi0wLjY1Yy0xLjY5LTEuNzk1LTQuNDMtMS43OTUtNi4xMiwwYy0xLjY5LDEuNzk1LTEuNjksNC43MDYsMCw2LjUwMmwwLjYxMiwwLjY1TDgsMTYgIGw2LjEyLTYuNTAybDAuNjEyLTAuNjVjMS42OS0xLjc5NSwxLjY5LTQuNzA2LDAtNi41MDJDMTMuMDQyLDAuNTUxLDEwLjMwMiwwLjU1MSw4LjYxMiwyLjM0N3oiLz48L3N2Zz4='}, 200, 200)"><i class="fas fa-heart"></i>
                                  
                                  </button>
                                  <button tool z-click="ctx.usePolygonTool(10, 0, 0.5)">
                                  <i class="fas fa-star"></i>
                                  </button>
                                  <button tool z-click="ctx.useTextTool()" title="Text" z-class="{selected:ctx.getCurrentTool()=='text'}">
                                    <i class="fas fa-font"></i>
                                  </button>
                                  <button tool z-click="ctx.insertImage()" title="Insert image">
                                    <i class="fas fa-image"></i>
                                  </button>
                                  <button tool z-click="ctx.cut()" title="Cut">
                                    <i class="fas fa-cut"></i>
                                  </button>
                                  <button tool z-click="ctx.copy()" title="Copy">
                                    <i class="fas fa-copy"></i>
                                  </button>
                                  <button tool z-click="ctx.paste()" title="Paste">
                                    <i class="fas fa-paste"></i>
                                  </button>
                                  <button tool z-click="ctx.undo()" z-disabled="!ctx.canUndo()">
                                    <i class="fas fa-undo"></i>
                                  </button>
                                  <button tool z-click="ctx.redo()" z-disabled="!ctx.canRedo()">
                                    <i class="fas fa-redo"></i>
                                  </button>
                                  <button tool z-click="ctx.zoomIn()">
                                    <i class="fas fa-search-plus"></i>
                                  </button>
                                  <button tool z-click="ctx.setZoom('page')">
                                    <i class="fas fa-compress-arrows-alt"></i>
                                  </button>
                                  <button tool z-click="ctx.zoomOut()">
                                    <i class="fas fa-search-minus"></i>
                                  </button>
                                </div>
                                <button z-show-popup="my-menu">Download</button>
                                <div z-has="AnyNode">
                                  <h3>Shape options</h3>
                                  <button z-click="ctx.deleteNodes()">Delete</button>
                                  <button z-click="ctx.bringToFront()">
                                    Move to front
                                  </button>
                                  <button z-click="ctx.sendToBack()">
                                    Send to back
                                  </button>
                                </div>
                                <div z-has="fontName">
                                  <h4>Font</h4>
                                  <select z-property="fontName">
                                    <option>Alice</option>
                                    <option>Arial</option>
                                    <option>Times New Roman</option>
                                    <option>Pacifico</option>
                                    <option>Anton</option>
                                    <option>Bebas Neue</option>
                                    <option>Bitter</option>
                                    <option>East Sea Dokdo</option>
                                    <option>Goldman</option>
                                    <option>Hanalei Fill</option>
                                    <option>Kufam</option>
                                    <option>Lustria</option>
                                    <option>Pirata One</option>
                                    <option>Poppins</option>
                                    <option>Raleway</option>
                                    <option>Redressed</option>
                                    <option>Roboto Condensed</option>
                                    <option>Slabo 27px</option>
                                    <option>Spectral</option>
                                    <option>Spectral SC</option>
                                    <option>Yeon Sung</option>
                                  </select>
                                </div>
                                <div z-has="fontSize">
                                  <h4>Font size</h4>
                                  <select z-property="fontSize">
                                    <option>8</option>
                                    <option>10</option>
                                    <option>12</option>
                                    <option>20</option>
                                    <option>24</option>
                                    <option>50</option>
                                    <option>60</option>
                                    <option>70</option>
                                    <option>80</option>
                                    <option>90</option>
                                    <option>100</option>
                                  </select>
                                </div>
                                <div z-has="textAlign">
                                  <h4>Text alignment</h4>
                                  <select z-property="textAlign">
                                    <option>left</option>
                                    <option>center</option>
                                    <option>right</option>
                                  </select>
                                </div>
                                <div z-has="fillStyle">
                                  <h3>Colours</h3>
                                  <div class="colour-picker" z-has="fillStyle">
                                    <div swatch z-property="fillStyle" z-colour></div>
                                    Fill style
                                  </div>
                                  <div class="colour-picker" z-has="strokeStyle">
                                    <div swatch z-property="strokeStyle" z-colour></div>
                                    Outline
                                  </div>
                                  <div class="colour-picker" z-has="background">
                                    <div swatch z-property="background" z-colour></div>
                                    Background
                                  </div>
                                </div>
                                <div z-has="arrowSize">
                                  <h3>Arrows</h3>
                                  <button class="option" z-property="arrowSize" z-value="0">None</button>
                                  <button class="option" z-property="arrowSize" z-value="10">Small</button>
                                  <button class="option" z-property="arrowSize" z-value="15">Large</button>
                                  <hr>
                                  <button class="option" z-property="arrowStyle" z-value="solid">Solid</button>
                                  <button class="option" z-property="arrowStyle" z-value="open">Open</button>
                                </div>
                                <div z-has="lineWidth">
                                  <h3>Line width</h3>
                                  <select z-property="lineWidth">
                                    <option value="0">None</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>4</option>
                                    <option>8</option>
                                  </select>
                                </div>
                                <div z-has="dashes">
                                  <h3>Line style</h3>
                                  <button class="option" z-property="dashes" z-value="">Solid</button>
                                  <button class="option" z-property="dashes" z-value="3,3">Dots</button>
                                  <button class="option" z-property="dashes" z-value="8,2">Dashes</button>
                                </div>
                                <div z-has="opacity">
                                  <h3>Opacity</h3>
                                  <input z-property="opacity" type="range" min="0.1" max="1.0" step="0.05">
                                </div>
                              </div>
                              <div style="display:flex;flex-flow:column;flex: 1 1 auto;min-width:0">
                                <div z-canvas></div>
                                <div class="pages">
                                  <button title="Insert page" z-click="ctx.insertPage()"><i class="fas fa-plus"></i></button>
                                  <button title="Delete page" z-click="ctx.deletePage()"><i class="fas fa-minus"></i></button>
                                  <div z-sort="ctx.movePage($from, $to)">
                                    <div z-for='mypage in ctx.getPageCount()' z-page="mypage" draggable="TRUE" z-sortable z-height="70" class="page" z-selected="mypage==ctx.getCurrentPage()" z-click="ctx.setCurrentPage(mypage)">
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div z-popup="my-menu" z-click-dismiss>
                                Filename: <input z-model='filename' z-focus><br>
                                <button z-click="ctx.download('png', filename+'.png')">PNG</button>
                                <button z-click="ctx.download('jpg', filename+'.jpg')">JPG</button>
                                <button z-click="ctx.download('svg', filename+'.svg')">SVG</button>
                                <button z-click="ctx.download('pdf', filename+'.pdf')">PDF</button>
                              </div>
                            </zwibbler>
                            <div style="margin-bottom: 20px;margin-left: 600px;margin-top: 20px;">
                                <input type="button" class="btn btn-primary savedrawingbtn" id="savedrawingbtn_<?php echo $count; ?>" value="Save This Drawing"/>
                            </div>
                        </div>
                    </div>
                <?php
                    $zwibbler_index = $zwibbler_index + 1;    
                    echo "<hr>";
                  }
                  ?>
        </div>
    <?php
    }
    ?>
    <br><br>
    <button style="float:right;position: relative;top: -50px;right: 15px;font-size: medium;" type="submit" class="btn btn-primary" name="submit" onclick="return check_validation();">Submit Assessment</button>
    <input type="hidden" id="question_count" name="question_count" value="<?php echo $count ?>">
    <input type="hidden" id="remarks" name="remarks" value="Submitted By Student">
    
    </div>
    </div>
    </form>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
            <div class="sidenav_assessment" id="sidebars">
                <h4 class="timer_heading">Remaining Time</h4>
                <div class="self_timer">
                    <div class="row">
                        <div class="col-md-12">
                            <p id="demo" style="font-size:30px"></p>
                        </div>
                    </div>
                </div>
                <br><br>
                <h4 class="timer_heading">Assessment Details</h4>
                <div class="card">
                  <div class="row" style="border: 13px #3498c6;border-style: inset;">
                      <div class="col-lg-4 col-sm-4">
                          <div class="text-center">
                              <img src="<?= base_url() ?>/uploads/default_pic.png" class='mt-3' alt="Indici-Edu" style="width:80%">
                          </div>
                      </div>
                      <div class="col-lg-8 col-sm-8">
                        <div class="container_after_timer">
                            <h4><b><?php echo $_SESSION['student_name'] ; ?></b></h4> 
                            <p class="detail_card_p"><?php echo $_SESSION['department_name']; ?> <?php echo $_SESSION['class_name'] ; ?> <?php echo $_SESSION['section_name'] ; ?></p> 
                            <p class="detail_card_p">Start Time: <?php echo $assessment_time_date[0]['start_time']; ?></p>
                            <p class="detail_card_p">End Time: <?php echo $assessment_time_date[0]['end_time']; ?></p>
                            <p class="detail_card_p"><b>Remarks / Instructions</b><br>
                                <?php echo $assessment_row->remarks; ?>
                            </p>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade eduModal" id="modal-4" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:100px;">
                <div class="modal-header">
                    <h4 class="modal-title" style="text-align:center;">Are you sure to Continue ?</h4>
                    <button type="button" class="close" data-dismiss="modal"aria-hidden="true">&times;</button>
                </div>
                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                    <a href="#" class="btn btn-danger" data-id="1" id="delete_link">Yes</a>
                    &nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-danger" data-dismiss="modal" style="background-color:#F44336!important;border:#f34336 1px solid!important">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://zwibbler.com/zwibbler-demo.js"></script>
    <script>
        var editor;
        $('.editor').each(function(){
        
            var $this = $(this);
            var ids = $this.attr('id');
            var splitted = ids.split('_');
            var question_no = splitted[1];
            CKEDITOR.plugins.addExternal('ckeditor_wiris', 'https://www.wiris.net/demo/plugins/ckeditor/', 'plugin.js');
              CKEDITOR.replace('answer_'+question_no, {
                 extraPlugins: 'ckeditor_wiris',
                 height: 200,
                 // Remove the redundant buttons from toolbar groups defined above.
                 removeButtons: 'Styles,removeFormat,Strike,Anchor,SpellChecker,PasteFromWord,Image,Source,Text,Copy,Paste,Cut,plaintext,Undo,Redo,About'
            });
            
        });
        
        
        <?php
        if($zwibbler_found > 0)
        {
        ?>
            var zwibbler_instance = [];
            $('.drawingtool').each(function(){
            
                var $this = $(this);
                var ids = $this.attr('id');
                var splitted = ids.split('_');
                var question_no = splitted[1];
                
                zwibbler_instance.push(Zwibbler.create("zwibbler_"+question_no, {
                             clickToDrawShapes: true
                }));
                
            });
        <?php
        }
        ?>
        
        
        
  
    </script>
    
    <script>
    // Set the date we're counting down to
    var aa = "<?php echo date('M d, Y' ,strtotime($assessment_time_date[0]['assessment_date']))." ".date('H:i:s' ,strtotime($assessment_time_date[0]['end_time'])); ?>";
    var countDownDate = new Date(aa).getTime();
    
    // Update the count down every 1 second
    var x = setInterval(function() {
    
      // Get today's date and time
      var now = new Date().getTime();
    
      // Find the distance between now and the count down date
      var distance = countDownDate - now;
    
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
      // Display the result in the element with id="demo"
      document.getElementById("demo").innerHTML = "<div class='col-md-4'><p class='times'>" + hours + "</p><p class='times_text'>hours</p></div> <div class='col-md-4'><p class='times'>" + minutes + "</p><p class='times_text'>minutes</p></div><div class='col-md-4'><p class='times'>"
      + seconds + "</p><p class='times_text'>seconds</p></div>";
      
      
      if( parseInt(hours) == 0 && parseInt(minutes) == 5 &&  parseInt(seconds) == 0 ){
          alert('5 minutes remaining please submit your paper');
      }
      
      if( parseInt(hours) == 0 && parseInt(minutes) == 3 &&  parseInt(seconds) == 0 ){
          alert('3 minutes remaining please submit your paper');
      }
      
      if( parseInt(hours) == 0 && parseInt(minutes) == 1 &&  parseInt(seconds) == 0 ){
          alert('1 minutes remaining please submit your paper');
      }
      
      if( parseInt(hours) == 0 && parseInt(minutes) == 0 &&  parseInt(seconds) == 0 ){
           $('#remarks').val('Time Expired And Auto Submitted');
           $('#answer_sheet').submit();
      }
    
      // If the count down is finished, write some text
      
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "EXPIRED";
        
      }
    }, 1000);
</script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>assets/audio/articulate.js"></script>
    <script src="<?php echo base_url() ?>assets/audio/articulate.min.js"></script>

    <script>
        function speak(obj) {
            $(obj).articulate('speak');
        };
        
        function check_validation()
        {
            var proceed = true;
            $('#modal-4').modal('show',{backdrop: 'static', keyboard: false}); //Added By tm
    
            document.getElementById('delete_link').onclick = function(){
               
              proceed = true;
            } 
            return proceed;
            
            
            
        }
    </script>
    
    <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
    
    <script type="text/javascript">
    
        
        $('.savedrawingbtn').click(function(){
                var $this = $(this);
                var ids = $this.attr('id');
                var splitted = ids.split('_');
                var count = splitted[1];
                var question_id = $("#zwibbler_question_"+count).val();
                var assessment_id = $('#assessment_id').val();
                
                var index = $("#zwibbler_index_"+count).val();
                
                
                var zwibbler = Zwibbler.create("zwibbler_"+count, {
                 clickToDrawShapes: true
                });
                
                var dataUrl = zwibbler_instance[index].save("png");
                
                
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>assessment_student/save_drawing_image/",
                    dataType: 'json',
                    data: {
                      base_file_parameters:dataUrl,
                      assessment_id : assessment_id,
                      question_id : question_id
                    },
                    success: function(result){
                      if(result.status == 200) {
                        // alert('File Saved Successfully!');
                        //location.reload();
                        $('#zwibbler_div_'+count).hide();
                        $('#zwibbler_span_'+count).html('File has been uploaded & Answer submitted');
                        
                      } else
                      if(result.status == 400) {
                        alert('Failed To Save The File!');
                      }
                      
                    }
                });
                
                
        });
        
        /*
        function upload_files(){
            
            $('.zwibblerabc').each(function(){
                var $this = $(this);
                var ids = $this.attr('id');
                var splitted = ids.split('_');
                var count = splitted[1];
                var question_id = $("#zwibbler_question_"+count).val();
                var assessment_id = $('#assessment_id').val();
                
                var index = $("#zwibbler_index_"+count).val();
                
                
                var zwibbler = Zwibbler.create("zwibbler_"+count, {
                 clickToDrawShapes: true
                });
                
                var dataUrl = zwibbler_instance[index].save("png");
                
                
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>assessment/save_drawing_image/",
                    dataType: 'json',
                    data: {
                      base_file_parameters:dataUrl,
                      assessment_id : assessment_id,
                      question_id : question_id
                    },
                    success: function(result){
                      if(result.status == 200) {
                        alert('File Saved Successfully!');
                        //location.reload();
                        $('#zwibbler_div_'+count).hide();
                        $('#zwibbler_span_'+count).html('File has been uploaded & Answer submitted');
                        
                      } else
                      if(result.status == 400) {
                        alert('Failed To Save The File!');
                      }
                      
                    }
                });
                
            });
        }
        */
    </script>
    
    <script>
        $(function() {
            var $sidebar   = $("#sidebars"), 
                $window    = $(window),
                offset     = $sidebar.offset(),
                topPadding = 15;
        
            $window.scroll(function() {
                if ($window.scrollTop() > offset.top) {
                    $sidebar.stop().animate({
                        marginTop: $window.scrollTop() - offset.top + topPadding
                    });
                } else {
                    $sidebar.stop().animate({
                        marginTop: 0
                    });
                }
            });
        });
    </script>



