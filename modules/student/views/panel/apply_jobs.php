<style>
  .job-container {
      max-width: 100%;
      margin: 0 auto;
  }
  .job-card {
      background: white;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      margin-bottom: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
  }
  .job-details {
      flex: 1;
  }
  .job h2 {
      margin: 0;
      color: #333;
  }
  .job p {
      margin: 5px 0;
      color: #666;
  }
  .job-icons {
      display: flex;
      gap: 15px;
      color: #555;
      font-size: 14px;
  }
  .job-icons span {
      display: flex;
      align-items: center;
  }
  .company-logo {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      object-fit: cover;
      border: 1px solid grey;
      margin-left: 15px;
      padding: 2px;
  }
  .apply-button {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 15px;
      background: #1c6ddf;
      color: white;
      text-decoration: none;
      border-radius: 5px;
  }
  .apply-button:hover {
      background: #1c54a3;
  }

  .stats-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
  }

  .stat-circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: #006dff94;
    color: white;
    display: flex;
    flex-direction: column; /* Stack number & text */
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: bold;
    text-align: center;
    border: 1px solid grey;
    padding: 2px;
  }
</style>

<?php
  $job_list = $this->db->select('j.*,c.institute_name as center_name,i.industry,d.department,jr.role,state.STATE_NAME,district.DISTRICT_NAME,c.logo,ei.about_company, ei.website,rir.id as rir, DATE_FORMAT(rir.created_at,"%d-%m-%Y") as applied_date,rir.status as rstatus')
                    ->from('jobs as j')
                    ->join('received_interview_request as rir', 'rir.student_id = '.$this->student_model->studentId().' and rir.job_id = j.id', 'left')
                    ->join('centers as c', 'c.id = j.employer_id', 'left')
                    ->join('employer_info as ei', 'ei.employer_id = c.id', 'left')
                    ->join('industry as i', 'i.id = j.industry_id', 'left')
                    ->join('department as d', 'd.id = j.department_id', 'left')
                    ->join('job_role as jr', 'jr.id = j.role_id', 'left')
                    ->join('state', 'state.STATE_ID = j.state_id', 'left')
                    ->join('district', 'district.DISTRICT_ID = j.city_id and district.STATE_ID = state.STATE_ID', 'left')
                    ->where('j.status','open')
                    ->order_by('j.id','DESC')
                    ->get()->result_array();

?>

<div class="job-container">
  <?php foreach($job_list as $key => $value) { ?>
  <div class="job-card">
      <div class="job-details">
          <h2><?= $value['job_title'] ?></h2>
          <p>Company: <?= $value['center_name'] ?> | &#127760; <?= $value['website'] ?></p>
          <div class="job-icons">
              <span><i class="fa-solid fa-briefcase"></i>&nbsp;<?= $value['experience'] ?> Yrs</span>
              <span><i class="fa-solid fa-indian-rupee-sign"></i>&nbsp;<?= $value['salary'] ?></span>
              <span><i class="fa-solid fa-location-dot"></i>&nbsp;<?= $value['work_location'] ?></span>
          </div>
          <p><i class="fa-solid fa-file-alt"></i>
            <?php echo sortString($value['job_highlights']); ?>
          </p>
          <p>
            <?php echo displayKeySkill($value['key_skills']); ?>
          </p>
          <?php if(empty($value['rir'])) { ?>
            <div class="job-icons" style="font-size: 10px;"> <?= timeAgo($value['timestamp']) ?></div>
            <a data-param='<?php echo json_encode(['job_id' => $value['id'], 'student_id' => $this->student_model->studentId()]); ?>' class="apply-button" style="cursor: pointer;" >Apply</a>
          <?php } else { ?>
            <div class="job-icons" style="font-size: 10px;"> Applied On <?= $value['applied_date'] ?></div>
            <label class="badge badge-info"><?= ucfirst($value['rstatus']) ?></label>
          <?php } ?>
      </div>

      <img src="<?= (file_exists('upload/' . $value['logo']) ? base_url('upload/' . $value['logo']) : base_url('assets/media/auth/welcome.png')) ?>" alt="<?= $value['center_name'] ?>" class="company-logo">
  </div>
  <?php } ?>
  <!-- <div class="job-card">
      <div class="job-details">
          <h2>Senior Software Engineer</h2>
          <p>Company: Irdeto ⭐ 1.0 | 2 Reviews</p>
          <div class="job-icons">
              <span>📅 8-13 Yrs</span>
              <span>💰 Not disclosed</span>
              <span>📍 Noida</span>
          </div>
          <p>Required Skills: Core Java, Linux, SQLite, Agile</p>
          <a href="#" class="apply-button">Apply Now</a>
      </div>
      <img src="irdeto_logo.png" alt="Irdeto Logo" class="company-logo">
  </div> -->
</div>
