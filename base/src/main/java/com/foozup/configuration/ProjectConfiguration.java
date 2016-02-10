package com.foozup.configuration;

import javax.sql.DataSource;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.Import;
import org.springframework.web.servlet.config.annotation.EnableWebMvc;
import org.springframework.web.servlet.config.annotation.ResourceHandlerRegistry;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurerAdapter;

import com.foozup.dao.AbstractDao;
import com.foozup.dao.city.CityDao;
import com.foozup.dao.city.CityDaoImpl;

@Configuration
@EnableWebMvc
@ComponentScan(basePackages = "com.foozup")
@Import(SwaggerConfiguration.class)
public class ProjectConfiguration extends WebMvcConfigurerAdapter {

	/*@Bean
	public DataSource getDataSource() {
		DriverManagerDataSource dataSource = new DriverManagerDataSource();
		dataSource.setDriverClassName("com.mysql.jdbc.Driver");
		dataSource.setUrl("jdbc:mysql://localhost:3306/world");
		dataSource.setUsername("root");
		dataSource.setPassword("root");

		return dataSource;
	}*/
	@Autowired
	private DataSource dataSource;

	@Bean
	public AbstractDao getAbstractDao() {
		return new AbstractDao(dataSource);
	}

	@Bean
	public CityDao fooService() {
		return new CityDaoImpl(getAbstractDao());
	}

	@Override
	public void addResourceHandlers(ResourceHandlerRegistry registry) {
		registry.addResourceHandler("swagger-ui.html").addResourceLocations("classpath:/META-INF/resources/");
		registry.addResourceHandler("/webjars/**").addResourceLocations("classpath:/META-INF/resources/webjars/");
		
	}
}
