package com.foozup.restaurant.model;

public class RestaurantBase {

	private Integer id;
	private String name;
	private String city;
	private Integer cityId;
	private Integer areaId;
	private String area;
	private String location;
	private Integer locationId;
	private String photo;
	private Integer cost;
	private String phoneNo;
	private String address;
	private Integer minDeliveryCost;
	public Integer getId() {
		return id;
	}
	public void setId(Integer id) {
		this.id = id;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public String getCity() {
		return city;
	}
	public void setCity(String city) {
		this.city = city;
	}
	public Integer getCityId() {
		return cityId;
	}
	public void setCityId(Integer cityId) {
		this.cityId = cityId;
	}
	public Integer getAreaId() {
		return areaId;
	}
	public void setAreaId(Integer areaId) {
		this.areaId = areaId;
	}
	public String getArea() {
		return area;
	}
	public void setArea(String area) {
		this.area = area;
	}
	public String getLocation() {
		return location;
	}
	public void setLocation(String location) {
		this.location = location;
	}
	public String getPhoto() {
		return photo;
	}
	public void setPhoto(String photo) {
		this.photo = photo;
	}
	
	public String getPhoneNo() {
		return phoneNo;
	}
	public void setPhoneNo(String phoneNo) {
		this.phoneNo = phoneNo;
	}
	public String getAddress() {
		return address;
	}
	public void setAddress(String address) {
		this.address = address;
	}
	public Integer getLocationId() {
		return locationId;
	}
	public void setLocationId(Integer locationId) {
		this.locationId = locationId;
	}
	public Integer getCost() {
		return cost;
	}
	public void setCost(Integer cost) {
		this.cost = cost;
	}
	public Integer getMinDeliveryCost() {
		return minDeliveryCost;
	}
	public void setMinDeliveryCost(Integer minDeliveryCost) {
		this.minDeliveryCost = minDeliveryCost;
	}
	

    @Override
    public boolean equals(Object o) {
        if ((o instanceof RestaurantBase) && (((RestaurantBase) o).getId() == this.id)) {
            return true;
        } else {
            return false;
        }
    }
 
    @Override
    public int hashCode() {
        int result = 0;
        result = result + name.length();
        result = (int) (id * 31);
        return result;
    }
	 
}
