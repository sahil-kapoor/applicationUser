package com.foozup.updates.model.dto;

public class UpdateFranchiseDto extends BaseUpdateDto{
	
	private boolean isFranchisee;
	private Integer franchiseeId;
	
	public boolean isFranchisee() {
		return isFranchisee;
	}
	public void setFranchisee(boolean isFranchisee) {
		this.isFranchisee = isFranchisee;
	}
	public Integer getFranchiseeId() {
		return franchiseeId;
	}
	public void setFranchiseeId(Integer franchiseeId) {
		this.franchiseeId = franchiseeId;
	}

}
