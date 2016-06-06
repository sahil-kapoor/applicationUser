package com.foozup.update.dao.rowMapper;

import java.sql.ResultSet;
import java.sql.SQLException;

import org.springframework.jdbc.core.RowMapper;

import com.foozup.updates.model.dto.UpdateRestDto;

public class UpdateBaseRestRowMapper implements RowMapper{
	
	public Object mapRow(ResultSet rs, int rowNum) throws SQLException {
		UpdateRestDto dto = new UpdateRestDto();
		dto.setId(rs.getInt("update_id"));
		dto.setRestaurntId(rs.getInt("rest_id"));
		dto.setActiveDays(rs.getString("active_days"));
		dto.setAllTime(rs.getInt("all_time")==0 ?false:true);
		if(dto.isAllTime()){
			dto.setEndTime(rs.getTime("rest_end_time"));
			dto.setStartTime(rs.getTime("rest_start_time"));
		}else{
			dto.setEndTime(rs.getTime("update_end_time"));
			dto.setStartTime(rs.getTime("update_start_time"));
		}
		dto.setPrimary(rs.getInt("is_primary")==0?false : true);
		dto.setUpdateText(rs.getString("update"));
		dto.setStartDate(rs.getDate("update_start_date"));
		dto.setEndDate(rs.getDate("update_end_date"));
		return dto;
	}
	
}
