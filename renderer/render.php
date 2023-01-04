<?php
// If there is no ID then go back to dashboard

$serverRoot = $_SERVER['DOCUMENT_ROOT'];
require($serverRoot . '/cema/cema.php');
header('Content-Type: application/json');

$id = intval($_GET['id']);

$statement = $cema->pdo->prepare("SELECT * FROM avatar where user_id = :id");
$statement->execute(array(':id' => $id));
$avatar = $statement->fetch();

if (!$avatar) {
  $users = $cema->query("SELECT * FROM users WHERE id = ?", [$id], false);
  if (!$users) {
    die(json_encode(["status" => "error", "error" => "User doesn't exist."]));
  }
  $statement = $conn->prepare("SELECT * FROM avatar WHERE user_id = :id");
  $statement->execute(array(':id' => $id));
  $avatar = $statement->fetch();
}


$statement = $conn->prepare("UPDATE users SET avatar_link  = :link WHERE id = :id");
$statement->execute(array(':link' => md5($id),  ':id' => $id));

$avatarImport = '
import bpy
import struct
from bpy import context
from mathutils import Euler
import math

bpy.ops.wm.open_mainfile(filepath="' . $serverRoot . '/renderer/avatar_1.blend")
def hex_to_rgb(value):
  gamma = 2.05
  lv = len(value)
  fin = list(int(value[i:i + lv // 3], 16) for i in range(0, lv, lv // 3))
  r = pow(fin[0] / 255, gamma)
  g = pow(fin[1] / 255, gamma)
  b = pow(fin[2] / 255, gamma)
  fin.clear()
  fin.append(r)
  fin.append(g)
  fin.append(b)
  return tuple(fin)

';
if(!$avatar) {   
   $colors = '
bpy.data.objects["Torso"].select = True
bpy.data.objects["Torso"].active_material.diffuse_color = hex_to_rgb("a9a9a9")
bpy.data.objects["LeftLeg"].select = True
bpy.data.objects["LeftLeg"].active_material.diffuse_color = hex_to_rgb("a9a9a9")
bpy.data.objects["RightLeg"].select = True
bpy.data.objects["RightLeg"].active_material.diffuse_color = hex_to_rgb("a9a9a9")
';
} else {
   $colors = '
bpy.data.objects["Torso"].select = True
bpy.data.objects["Torso"].active_material.diffuse_color = hex_to_rgb("'. $avatar['torso_color'] . '")
bpy.data.objects["LeftLeg"].select = True
bpy.data.objects["LeftLeg"].active_material.diffuse_color = hex_to_rgb("' . $avatar['left_leg_color'] . '")
bpy.data.objects["RightLeg"].select = True
bpy.data.objects["RightLeg"].active_material.diffuse_color = hex_to_rgb("' . $avatar['right_leg_color'] . '")
bpy.data.objects["Head"].select = True
bpy.data.objects["Head"].active_material.diffuse_color = hex_to_rgb("' . $avatar['head_color'] . '")
bpy.data.objects["RightArm"].select = True
bpy.data.objects["RightArm"].active_material.diffuse_color = hex_to_rgb("' . $avatar['right_arm_color'] . '")
bpy.data.objects["LeftArm"].select = True
bpy.data.objects["LeftArm"].active_material.diffuse_color = hex_to_rgb("' . $avatar['left_arm_color'] . '")
';
}

if ($avatar && $avatar['face'] != null) {
  $face = '
Face_Image = bpy.data.images.load(filepath="' . $serverRoot . '/res/textures/faces/' . $avatar['face'] . '.png")
bpy.data.textures["Face"].image = Face_Image'
;
} else {
  $face = '
HeadImg = bpy.data.images.load(filepath="' . $serverRoot . '/cdn/img/edewd.png")
bpy.data.textures["Face"].image = HeadImg';
}


$shirt = '
Shirt_Image = bpy.data.images.load(filepath="' . $serverRoot . '/cdn/img/template.png")
bpy.data.textures["Shirt"].image = Shirt_Image
';
$pants = '';

if ($avatar && $avatar['pants'] != null) {
  $pants = '
LeftLegImg = bpy.data.images.load(filepath="' . $serverRoot . '/cdn/img/' . $avatar['pants'] . '.png")
LeftLegTex = bpy.data.textures.new("ColorTex", type = "IMAGE")
LeftLegTex.image = LeftLegImg
LeftLegslot = bpy.data.objects["LeftLeg"].active_material.texture_slots.add()
LeftLegslot.texture = LeftLegTex
RightLegImg = bpy.data.images.load(filepath="' . $serverRoot . '/cdn/img/' . $avatar['pants'] . '.png")
RightLegTex = bpy.data.textures.new("ColorTex", type = "IMAGE")
RightLegTex.image = RightLegImg
RightLegslot = bpy.data.objects["RightLeg"].active_material.texture_slots.add()
RightLegslot.texture = RightLegTex
';
}

$hat = '';

if ($avatar && $avatar['hat'] != null) {
}
$hat = '
hatpath = "' . $serverRoot . '/renderer/res/models/hats/1.obj"
import_hat = bpy.ops.import_scene.obj(filepath=hatpath)
hat = bpy.context.selected_objects[0]
bpy.context.selected_objects[0].data.name = "hat"
hatImg = bpy.data.images.load(filepath="' . $serverRoot . '/renderer/res/textures/hats/1.png")
hatTex = bpy.data.textures.new("ColorTex", type = "IMAGE")
hatTex.image = hatImg
hatMat = bpy.data.materials.new("MaterialName")
hatMat.diffuse_shader = "LAMBERT"
hatSlot = hatMat.texture_slots.add()
hatSlot.texture = hatTex
hat.active_material = hatMat
';

$hat2 = '';

if ($avatar && $avatar['hat2'] != null) {
}
$hat2 = '
hat2path = "' . $serverRoot . '/renderer/res/models/hats/2.obj"
import_hat2 = bpy.ops.import_scene.obj(filepath=hat2path)
hat2 = bpy.context.selected_objects[0]
bpy.context.selected_objects[0].data.name = "hat2"
hat2Img = bpy.data.images.load(filepath="' . $serverRoot . '/renderer/res/textures/hats/2.png")
hat2Tex = bpy.data.textures.new("ColorTex", type = "IMAGE")
hat2Tex.image = hat2Img
hat2Mat = bpy.data.materials.new("MaterialName")
hat2Mat.diffuse_shader = "LAMBERT"
hat2Slot = hat2Mat.texture_slots.add()
hat2Slot.texture = hat2Tex
hat2.active_material = hat2Mat
';



$save = "
for ob in bpy.context.scene.objects:
    if ob.type == 'MESH':
        ob.select = True
        bpy.context.scene.objects.active = ob
    else:
        ob.select = False
bpy.ops.view3d.camera_to_view_selected()

bpy.data.scenes['Scene'].render.filepath = '" . $serverRoot . '/cdn/img/avatar/' . md5($id)  . ".png'
bpy.ops.render.render( write_still=True )
";

$save_thumb = '
obj = bpy.data.objects["Camera"]
obj.location.x = -0.521939
obj.location.y = -4.31824
obj.location.z = 7.13066
obj.rotation_euler = Euler((math.radians(90),math.radians(0),math.radians(0)), "XYZ")

for ob in bpy.context.scene.objects:
    if ob.type == "MESH":
        ob.select = True
        bpy.context.scene.objects.active = ob
    else:
        ob.select = False

bpy.data.scenes["Scene"].render.filepath = "' . $serverRoot . '/cdn/img/avatar/thumbnail/' . md5($id)  . '.png"
bpy.ops.render.render( write_still=True )
';

$python = "
$avatarImport
$colors
$hat
$hat2
$shirt
$pants
$face
$save
";

$python_thumb = "
$avatarImport
$colors
$hat
$hat2
$shirt
$pants
$face
$save_thumb
";

$pyFileName = "" . $serverRoot . "/renderer/python/1.py";
$pyFileName_thumb = "" . $serverRoot . "/renderer/python/1_thumb.py";

file_put_contents($pyFileName, $python);
file_put_contents($pyFileName_thumb, $python_thumb);


// Weird issue where blender wont start on local servers if you dont have this line about the path in there
putenv('PATH=' . $_SERVER['PATH']);
//   run in back       script       server path                 link           file | other shit idk what does
$output = exec("blender --background --python " . $_SERVER['DOCUMENT_ROOT'] . '/renderer/python/' .  "1.py -noaudio -nojoystick");
$output = exec("blender --background --python " . $_SERVER['DOCUMENT_ROOT'] . '/renderer/python/' .  "1_thumb.py -noaudio -nojoystick");
