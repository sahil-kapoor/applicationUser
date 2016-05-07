package com.foozup.update.dao;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.foozup.dao.AbstractDao;

public class UpdatesDaoImpl implements IUpdatesDao {

	private AbstractDao abstractDao;
	private static final Logger logger = LoggerFactory.getLogger(UpdatesDaoImpl.class);;

	public UpdatesDaoImpl(AbstractDao abstractDao) {
		this.abstractDao = abstractDao;
	}
}
