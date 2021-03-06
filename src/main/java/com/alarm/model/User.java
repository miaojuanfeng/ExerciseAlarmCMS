package com.alarm.model;

import java.util.Date;
import java.util.Set;

public class User {
    private Integer id;

    private String username;
    
    private String password;
    
    private Integer number;

    private String nickname;
    
    private Integer unread;
    
    private String data;

    private String platform;

    private Date createDate;

    private Date modifyDate;

    private Integer deleted;
    
    private Set<Discuss> discuss;

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username == null ? null : username.trim();
    }

    public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password == null ? null : password.trim();
	}

	public Integer getNumber() {
		return number;
	}

	public void setNumber(Integer number) {
		this.number = number;
	}

	public String getNickname() {
        return nickname;
    }

    public void setNickname(String nickname) {
        this.nickname = nickname == null ? null : nickname.trim();
    }

    public Integer getUnread() {
		return unread;
	}

	public void setUnread(Integer unread) {
		this.unread = unread;
	}

	public String getData() {
		return data;
	}

	public void setData(String data) {
		this.data = data;
	}

	public String getPlatform() {
        return platform;
    }

    public void setPlatform(String platform) {
        this.platform = platform == null ? null : platform.trim();
    }

    public Date getCreateDate() {
        return createDate;
    }

    public void setCreateDate(Date createDate) {
        this.createDate = createDate;
    }

    public Date getModifyDate() {
        return modifyDate;
    }

    public void setModifyDate(Date modifyDate) {
        this.modifyDate = modifyDate;
    }

    public Integer getDeleted() {
        return deleted;
    }

    public void setDeleted(Integer deleted) {
        this.deleted = deleted;
    }

	public Set<Discuss> getDiscuss() {
		return discuss;
	}

	public void setDiscuss(Set<Discuss> discuss) {
		this.discuss = discuss;
	}
    
}
