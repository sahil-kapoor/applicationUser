package com.foozup.updates.service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.updates.service.helper.IUpdateServiceHelper;

@Service("updatesService")
public class UpdatesServiceImpl implements IUpdatesService{

	@Autowired
	private IUpdateServiceHelper updateServiceHelper;
}
