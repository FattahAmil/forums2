<html>
<head>
    <meta charset="utf-8">
    <title>My Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./assets/js/likes.js"></script>
</head>
<?php
require_once 'connection.php';

$stmt = $conn->query("SELECT id_post, id_user, content, media, DATE_FORMAT(post_date, '%M %d, %Y %H:%i:%S') AS date 
FROM posts P,follow F 
WHERE P.id_user=F.id_following 
  AND F.id_follower=".$_SESSION['id_session']." 
UNION
(SELECT id_post, id_user, content, media, DATE_FORMAT(post_date, '%M %d, %Y %H:%i:%S') AS date 
FROM posts 
WHERE id_user=".$_SESSION['id_session']." )ORDER BY date DESC");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $post_id = $row['id_post'];
  $user_id = $row['id_user'];
  $content = $row['content'];
  $media = $row['media'];
  $post_date = $row['date'];

  // Retrieve information about the user who posted the post
  $stmt_user = $conn->prepare("SELECT * FROM users WHERE id_user=:user_id");
  $stmt_user->bindParam(':user_id', $user_id);
  $stmt_user->execute();
  $result_user = $stmt_user->fetch(PDO::FETCH_ASSOC);
  $username = $result_user['nom_user'] . ' ' . $result_user['prenom_user'];
  $profile_image = $result_user['imgprfl_user'];

  // Retrieve the number of likes for the post
  $stmt_numlike = $conn->prepare("SELECT COUNT(*) FROM likes WHERE id_post = :id_post");
  $stmt_numlike->bindParam(':id_post', $post_id);
  $stmt_numlike->execute();
  $num_likes = $stmt_numlike->fetchColumn();

  // Display the post ?>
  <div class='news-feed news-feed-post'>
  <div class='post-header d-flex justify-content-between align-items-center'>
  <div class='image'>
 <?php if (!empty($profile_image)) { ?>
    <a href='my-profile.php'><img src='<?php echo $profile_image ;?>' class='rounded-circle' alt='Profile Image' width="60"></a>
 <?php } ?>
 </div>
  <div class='info ms-3'>
  <span class='name'><a href='my-profile.html'><?php echo $username ;?></a></span>
  <span class='small-text'><a href='#'><?php echo $post_date;?></a></span>
  </div>
  <div class='dropdown'>
  <button class='dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'><i class='flaticon-menu'></i></button>
  <ul class='dropdown-menu'>
  <li><a class='dropdown-item d-flex align-items-center' href='#'><i class='flaticon-edit'></i> Edit Post</a></li>
  <li><a class='dropdown-item d-flex align-items-center' href='#'><i class='flaticon-private'></i> Hide Post</a></li>
  <li><a class='dropdown-item d-flex align-items-center' href='#'><i class='flaticon-trash'></i> Delete Post</a></li>
  </ul>
  </div>
  </div>
  <div class="post-body">
                                    <p><?php echo $content;  ?></p>
                                    <div class="post-image">
                                    <?php if (!empty($media)) {
                                    if (pathinfo($media, PATHINFO_EXTENSION) === 'mp4') { ?>
                                    
                                       <video style="max-width: 100%;height: auto;display: inline-block;" controls><source src='./action/uploads/<?php echo $media ;?>' type='video/mp4'></video>
                                 <?php  } else { ?>
                                       <img src='./action/uploads/<?php echo $media ;?>'>
                                    <?php }
                                 }
                                 ?>
                                    </div>
                                    <ul class="post-meta-wrap d-flex justify-content-between align-items-center">
                                        
                                        <li class="post-react">
                                        <a href="#" class="like-button" data-type='like' data-post-id="<?php echo $row['id_post']; ?>"
                                         onclick="addLike(<?php echo $row['id_post']; ?>, 'like')" a>
                                            <i class="flaticon-like"></i>
                                            <span>Like</span>
                                            <span class="number"><?php echo $num_likes; ?></span>
                                        </a>
                                        </li>
                                        <ul class="react-list">
                                        <li>
                                            <a href="#" data-type="like"  data-post-id="<?php echo $row['id_post']; ?>"
                                             onclick="addLike(<?php echo $row['id_post']; ?>, 'like')">
                                                <img src="assets/images/react/react-1.png" alt="Like">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-type="love" data-type="love" data-post-id="<?php echo $row['id_post']; ?>"
                                               onclick="addLike(<?php echo $row['id_post']; ?>, 'love')">
                                                <img src="assets/images/react/react-2.png" alt="Love">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-type="thankful" data-post-id="<?php echo $row['id_post']; ?>"
                                             onclick="addLike(<?php echo $row['id_post']; ?>, 'thankful')">
                                                <img src="assets/images/react/react-3.png" alt="thankful">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-type="haha" data-post-id="<?php echo $row['id_post']; ?>"
                                             onclick="addLike(<?php echo $row['id_post']; ?>, 'haha')">
                                                <img src="assets/images/react/react-7.png" alt="haha">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-type="wow" data-post-id="<?php echo $row['id_post']; ?>"
                                             onclick="addLike(<?php echo $row['id_post']; ?>, 'wow')">
                                                <img src="assets/images/react/react-4.png" alt="Wow">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-type="sad" data-post-id="<?php echo $row['id_post']; ?>"
                                             onclick="addLike(<?php echo $row['id_post']; ?>, 'sad')">
                                                <img src="assets/images/react/react-5.png" alt="Sad">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-type="angry" data-post-id="<?php echo $row['id_post']; ?>"
                                             onclick="addLike(<?php echo $row['id_post']; ?>, 'angry')">
                                                <img src="assets/images/react/react-6.png" alt="Angry">
                                            </a>
                                        </li>
                                        </ul>
                                        </li>
                                        <li class="post-comment">
                                            <a href="#"><i class="flaticon-comment"></i><span>Comment</span> <span class="number">599 </span></a>
                                        </li>
                                        <li class="post-share">
                                            <a href="#"><i class="flaticon-share"></i><span>Share</span> <span class="number">24 </span></a>
                                        </li>
                                    </ul>
                                    <div class="post-comment-list">
                                        <div class="comment-list">
                                            <div class="comment-image">
                                                <a href="my-profile.html"><img src="assets/images/user/user-33.jpg" class="rounded-circle" alt="image"></a>
                                            </div>
                                            <div class="comment-info">
                                                <h3>
                                                    <a href="my-profile.html">David Moore</a>
                                                </h3>
                                                <span>5 Mins Ago</span>
                                                <p>Donec rutrum congue leo eget malesuada nulla quis lorem ut libero malesuada feugiat donec rutrum congue leo eget malesuada donec rutrum congue leo eget malesuada. Praesent sapien massa convallis a pellentesque non nisi curabitur non nulla sit amet nisl tempus convallis lectus.</p>
                                                <ul class="comment-react">
                                                    <li><a href="#" class="like">Like(2)</a></li>
                                                    <li><a href="#">Reply</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="comment-list">
                                            <div class="comment-image">
                                                <a href="my-profile.html"><img src="assets/images/user/user-34.jpg" class="rounded-circle" alt="image"></a>
                                            </div>
                                            <div class="comment-info">
                                                <h3>
                                                    <a href="my-profile.html">Claire P. Toy</a>
                                                </h3>
                                                <span>45 Mins Ago</span>
                                                <p>Donec rutrum congue leo eget malesuada praesent sapien massa convallis a pellentesque nec egestas non nisi curabitur non nulla sit amet nisl tempus convallis quis ac lectus.</p>
                                                <ul class="comment-react">
                                                    <li><a href="#" class="like">Like(12)</a></li>
                                                    <li><a href="#">Reply</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="comment-list">
                                            <div class="comment-image">
                                                <a href="my-profile.html"><img src="assets/images/user/user-8.jpg" class="rounded-circle" alt="image"></a>
                                            </div>
                                            <div class="comment-info">
                                                <h3>
                                                    <a href="my-profile.html">Karen Williams</a>
                                                </h3>
                                                <span>5 Mins Ago</span>
                                                <p>Donec rutrum congue leo eget malesuada nulla quis lorem ut libero malesuada feugiat donec rutrum congue leo eget.</p>
                                                <ul class="comment-react">
                                                    <li><a href="#" class="like">Like(2)</a></li>
                                                    <li><a href="#">Reply</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="more-comments">
                                            <a href="#">More Comments+</a>
                                        </div>
                                    </div>
                                    <form class="post-footer">
                                        <div class="footer-image">
                                            <a href="#"><img src="assets/images/user/user-1.jpg" class="rounded-circle" alt="image"></a>
                                        </div>
                                        <div class="form-group">
                                            <textarea name="message" class="form-control" placeholder="Write a comment..."></textarea>
                                            <label><a href="#"><i class="flaticon-photo-camera"></i></a></label>
                                        </div>
                                    </form>
                                </div>
 </div> <?php
}
?>