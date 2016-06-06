package com.foozup.updates.model.dto;

public class UpdateRestDto extends BaseUpdateDto {
	private boolean isPrimary;

	public boolean isPrimary() {
		return isPrimary;
	}

	public void setPrimary(boolean isPrimary) {
		this.isPrimary = isPrimary;
	}

}
