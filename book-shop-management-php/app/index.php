<?php
require __DIR__.'/db.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $act=$_POST['action']??'';
 if($act==='save'){
  $id=(int)($_POST['id']??0); $t=trim($_POST['title']??''); $a=trim($_POST['author']??''); $c=trim($_POST['category']??''); $p=(float)($_POST['price']??0); $s=(int)($_POST['stock']??0);
  if($t!==''&&$a!==''&&$p>0&&$s>=0){
   if($id){$q=$pdo->prepare('UPDATE books SET title=?,author=?,category=?,price=?,stock=? WHERE id=?');$q->execute([$t,$a,$c,$p,$s,$id]);$msg='Book updated.';}
   else{$q=$pdo->prepare('INSERT INTO books(title,author,category,price,stock) VALUES(?,?,?,?,?)');$q->execute([$t,$a,$c,$p,$s]);$msg='Book added.';}
  }else $msg='Enter valid details.';
 }elseif($act==='delete'){$q=$pdo->prepare('DELETE FROM books WHERE id=?');$q->execute([(int)$_POST['id']]);$msg='Book deleted.';}
}
$edit=null;if(isset($_GET['edit'])){$q=$pdo->prepare('SELECT * FROM books WHERE id=?');$q->execute([(int)$_GET['edit']]);$edit=$q->fetch();}
$search=trim($_GET['search']??'');if($search){$q=$pdo->prepare('SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY id DESC');$v="%$search%";$q->execute([$v,$v]);$books=$q->fetchAll();}else $books=$pdo->query('SELECT * FROM books ORDER BY id DESC')->fetchAll();
$count=$pdo->query('SELECT COUNT(*) FROM books')->fetchColumn();function h($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Book Shop Management</title><link rel="stylesheet" href="style.css"></head><body>
<header><div><h1>📚 Book Shop Management</h1><p>Inventory Dashboard</p></div><b><?=h($count)?> Books</b></header><main>
<?php if($msg):?><div class="notice"><?=h($msg)?></div><?php endif;?>
<section><h2><?=$edit?'Update Book':'Add New Book'?></h2><form method="post" class="grid"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=h($edit['id']??'')?>"><?php foreach(['title'=>'Book title','author'=>'Author','category'=>'Category','price'=>'Price (₹)','stock'=>'Stock'] as $k=>$label):?><input name="<?=$k?>" placeholder="<?=$label?>" value="<?=h($edit[$k]??'')?>" <?=in_array($k,['title','author','price','stock'])?'required':''?> <?=in_array($k,['price','stock'])?'type="number" min="0" step="0.01"':''?>><?php endforeach;?><button><?=$edit?'Save Changes':'Add Book'?></button><?php if($edit):?><a class="btn muted" href="index.php">Cancel</a><?php endif;?></form></section>
<section><div class="bar"><h2>Book Inventory</h2><form><input name="search" placeholder="Search title or author" value="<?=h($search)?>"><button>Search</button><a class="btn muted" href="index.php">Reset</a></form></div><div class="scroll"><table><tr><th>ID</th><th>Title</th><th>Author</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
<?php foreach($books as $x):?><tr><td><?=h($x['id'])?></td><td><?=h($x['title'])?></td><td><?=h($x['author'])?></td><td><?=h($x['category'])?></td><td>₹<?=number_format($x['price'],2)?></td><td><?=h($x['stock'])?></td><td><a class="btn edit" href="?edit=<?=$x['id']?>">Edit</a><form class="inline" method="post" onsubmit="return confirm('Delete book?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$x['id']?>"><button class="danger">Delete</button></form></td></tr><?php endforeach;?>
<?php if(!$books):?><tr><td colspan="7">No books found.</td></tr><?php endif;?></table></div></section></main><footer>PHP 8.3 · MySQL · Docker · Kubernetes</footer></body></html>